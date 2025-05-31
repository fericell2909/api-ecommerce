<?php

namespace App\Modules\Auth\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Modules\Auth\Controllers\Auth\Controller;
use App\Modules\Auth\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Modules\Api\Models\Status;
use App\Modules\Auth\Repositories\AuthRepository;
use App\Modules\PermissionsRoles\Repositories\RoleRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Modules\Auth\Models\User;
use App\Modules\PermissionsRoles\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\StatefulGuard;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
	/**
	 * Response trait to handle return responses.
	 */
	use ResponseTrait;

	/**
	 * Auth related functionalities.
	 *
	 * @var AuthRepository
	 */
	public $authRepository;

	// @var RoleRepository
	public $RoleRepository;
	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct(AuthRepository $ar, RoleRepository $roleRepository)
	{
		$this->authRepository = $ar;
		$this->RoleRepository = $roleRepository;
	}

	/**
	 * @OA\POST(
	 *     path="/api/auth/login",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="HU-000: Iniciar Sesión",
	 *     description="HU-000: Iniciar Sesión",
     *     security={{}},
	 *     @OA\RequestBody(
	 *          @OA\JsonContent(
	 *              type="object",
     *              required={"email", "password"},
	 *              @OA\Property(property="email", type="string", example="fericell29091@gmail.com"),
	 *              @OA\Property(property="password", type="string", example="abcd1234")
	 *          ),
	 *      ),
	 *      @OA\Response(response=200, description="Acceso Correcto.",  @OA\JsonContent(ref="#/components/schemas/ResponseLogin200Schema")),
     *      @OA\Response(response=400, description="Solicitu Inválida",  @OA\JsonContent(ref="#/components/schemas/ResponseLogin400Schema")),
	 *      @OA\Response(response=403, description="Acceso Restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseLogin403Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseLogin500Schema"))
	 * )
	 */
	public function login(LoginRequest $request): JsonResponse
	{
		try {
			$credentials = $request->only('email', 'password');
			$data = null;
			if ($token = $this->guard()->attempt($credentials)) {
				$user = $this->guard()->user();

                if($user->status_id !== Status::ACTIVE){
                    return $this->responseError(null, 'El Usuario no se encuentra activo.', Response::HTTP_FORBIDDEN);
                }

                $data = $this->respondWithToken($token);

			} else {
				return $this->responseError(null, 'Acceso Incorrecto.', Response::HTTP_FORBIDDEN);
			}

            unset($data['status_id']);

            return $this->responseSuccess($data,  'Acceso Correcto.');

        } catch (\Exception $e) {
			Log::error("ERROR :::  Controlador : AuthController ::: Metodo : login");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
		}
	}




	public function profile()
	{
	}

	/**
	 * @OA\POST(
	 *     path="/api/auth/logout",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="HU-000 : Cerrar Sesión",
	 *     description="Cerrar Sesión del Usuario Autenticado.",
	 *     @OA\Response(response=200, description="Cierre de Sesión." , @OA\JsonContent(ref="#/components/schemas/ResponseLogout200Schema")),
     *     @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *     @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema")),
     *     security={{"bearer":{}}},
	 * )
	 */
	public function logout(): JsonResponse
	{
		try {
			$this->guard()->logout();
			return $this->responseSuccess(null, 'Cierre sesión exitosamente');
		} catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : AuthController ::: logout");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
			return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
		}
	}

	// /**
	//  * @OA\POST(
	//  *     path="/api/auth/refresh",
	//  *     tags={"Authentication"},
	//  *     summary="Refresh",
	//  *     description="Refresh",
	//  *     security={{"bearer":{}}},
	//  *     @OA\Response(response=200, description="Refresh" ),
	//  *     @OA\Response(response=400, description="Bad request"),
	//  *     @OA\Response(response=404, description="Resource Not Found"),
	//  * )
	//  */
	public function refresh(): JsonResponse
	{
		try {
			$data = $this->respondWithToken($this->guard()->refresh());
			return $this->responseSuccess($data, 'Token Refreshed Successfully !');
		} catch (\Exception $e) {
			return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function respondWithToken($token): array
	{
		$user =  User::where('id', $this->guard()->user()->id)->with('roles', 'file', 'status')->first();
        $roles = Role::select('code','name')->get();

		$data = [
            'token_type' => 'Bearer',
			'accessToken' => $token,
			'expires_in' => $this->guard()->factory()->getTTL() * 1,
			'id' => $user->id,
            'email' => $user->email,
			'name' => $user->name,
            'surnames'  => $user->surnames,
            'uroles' => $user->roles,
			'language' => app()->getLocale(),
            'uroles' => $user->roles,
            'roles' => $roles,
            'request_new_password' => $user->request_new_password,
            'status_id'=> $user->status_id
		];

		return $data;
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return Guard|StatefulGuard|JWTGuard
	 */
	public function guard(): Guard|StatefulGuard|JWTGuard
	{
		return Auth::guard();
	}


}
