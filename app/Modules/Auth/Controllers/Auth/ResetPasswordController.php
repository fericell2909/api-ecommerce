<?php

namespace App\Modules\Auth\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\ResetPasswordRequest;
use App\Modules\Auth\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use  App\Modules\Auth\Repositories\ResetPasswordRepository;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait, HelperTrait;

    /**
     * Auth related functionalities.
     *
     * @var ResetPasswordRepository
     */
    public $resetPasswordRepository;

    /**
     * Auth related functionalities.
     *
     * @var AuthRepository
     */
    public $AuthRepository;

    /**
     * Create a new ForgotController instance.
     *
     * @return void
     */
    public function __construct(ResetPasswordRepository $ar, AuthRepository $authr)
    {
        $this->resetPasswordRepository = $ar;
        $this->AuthRepository = $authr;
    }

    /**
     * @OA\GET(
     *     path="/api/auth/forgot/password/validate/{token}",
     *     tags={"Gestión de Usuarios"},
     *     summary="HU-017 Cambiar Contraseña (Validar Token)",
     *     security={{}},
     *     description="Se valida que el token Exista y que no haya expirado.",
     *     @OA\Parameter(name="token", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Retorna proceso exitoso" , @OA\JsonContent(ref="#/components/schemas/ResetPassword200Schema")),
     *     @OA\Response(response=400, description="Solictud erronea" , @OA\JsonContent(ref="#/components/schemas/ResetPassword400Schema")),
     *     @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *     @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function token($token): JsonResponse
    {
        try {

            if(trim($token) === "") {

                return $this->responseError(['token' => 'Debe Ingresar un token.'], "El token no es válido", Response::HTTP_BAD_REQUEST);

            }

            $response = $this->resetPasswordRepository->isvalidate($token);

            if($response) {
                return $this->responseSuccess($response, "El token es válido.", Response::HTTP_OK);
            }

            return $this->responseError(null, "El token no es válido.", Response::HTTP_FORBIDDEN);

        } catch (\Exception $e) {

            Log::error("ERROR :::  Controlador : ResetPasswordController ::: Metodo : token");
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/auth/forgot/password/change",
     *     tags={"Gestión de Usuarios"},
     *     summary="HU-017 Cambiar Contraseña (Actualizar Contraseña con Token)",
     *     security={{}},
     *     description="Change Password by sent token email",
     *     @OA\RequestBody(
	 *          @OA\JsonContent(
	 *              type="object",
     *              required={"token", "password", "confirm_password"},
	 *              @OA\Property(property="token", type="string", example="2Ywesdtsdvsññnlñasdkjas"),
	 *              @OA\Property(property="password", type="string", example="abcd1234"),
     *              @OA\Property(property="confirm_password", type="string", example="abcd1234")
	 *          ),
	 *      ),
     *     operationId="change",
     *     @OA\Response(response=200, description="Retorna proceso exitoso" , @OA\JsonContent(ref="#/components/schemas/ResetPasswordChange200Schema")),
     *     @OA\Response(response=400, description="Solictud erronea" , @OA\JsonContent(ref="#/components/schemas/ResetPasswordChange400Schema")),
     *     @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *     @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function change(ResetPasswordRequest $request): JsonResponse
    {

        try {


            $response = $this->resetPasswordRepository->change($request->all());

            if($response) {
                return $this->responseSuccess($response, "Su contraseña ha sido actualizada exitosamente.", Response::HTTP_OK);
            }

            return $this->responseError(null, "Error al actualizar la nueva contraseña.", Response::HTTP_FORBIDDEN);

        } catch (\Exception $e) {

            Log::error("ERROR :::  Controlador : ResetPasswordController ::: Metodo : change");
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }
}
