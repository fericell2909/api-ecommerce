<?php

namespace App\Modules\Auth\Controllers\User;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Events\UserCreated;
use App\Modules\Auth\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use App\Modules\Auth\Requests\UserStoreRequest;
use App\Modules\Auth\Requests\UserUpdatemeRequest;
use App\Modules\Auth\Requests\UserUpdateRequest;
use App\Modules\Auth\Requests\UserUpdatemepasswordRequest;
use App\Modules\Auth\Requests\UserChangeStatusRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class UserController extends Controller
{
    use ResponseTrait;

    private $userRepository;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

	/**
	 * @OA\POST(
	 *     path="/api/user/create",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="HU-019: Crear Nuevo Usuario",
	 *     description="HU-019: Crear Nuevo Usuario y la contraseña inicial le llegará al correo electrónico que se especifique.",
	 *     @OA\RequestBody(
	 *          @OA\JsonContent(
	 *              type="object",
	 *              @OA\Property(property="name", type="string", example="Marco"),
     *              @OA\Property(property="surnames", type="string", example="Estrada López"),
	 *              @OA\Property(property="email", type="string", example="demo@bruzzoneygonzalez.com"),
     *             @OA\Property(property="roles", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="role_id", type="integer", example=1),
     *                 ),
     *             ),
	 *          ),
	 *      ),
	 *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Respuesta de Creación de Usuario Exitoso.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
     *      @OA\Response(response=400, description="Error en los datos que se necesita para la solicitud.", @OA\JsonContent(ref="#/components/schemas/ResponseUser400CreateSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
	 * )
	 */

	public function create(UserStoreRequest $request)
	{
		try {

            $pass =  Str::random(2).env('INITIAL_PASS_USER').Str::random(2);

			$user = $this->userRepository->register($request->all(),$pass);

			if ($user) {

                event(new UserCreated($user, $pass));

                return $this->responseSuccess($user,'Usuario Creado Exitosamente.' , Response::HTTP_OK);

			} else {
                return $this->responseError(null, 'Acceso Restringido', Response::HTTP_FORBIDDEN);
            }

		} catch (\Exception $e) {
			Log::error("ERROR :::  Controlador : UserController ::: create");
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
		}
	}

    /**
	 * @OA\GET(
	 *     path="/api/user/me",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="Obtener los datos del usuario autenticado",
     *     description="Obtener los datos del usuario autenticado",
     *     operationId="meUser",
	 *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Respuesta de Obtención de Datos Exitoso.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
	 * )
	 */
	public function me(): JsonResponse
	{
		try {

            $user = $this->userRepository->user();

			if(is_null($user))
                return $this->responseError(null, "EL usuario no ha sido encontrado.", Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($user, "Usuario obtenido exitosamente.",Response::HTTP_OK);

		} catch (\Exception $e) {
			Log::error("ERROR :::  Controlador : UserController ::: me");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
		}
	}

    /**
    * @OA\GET(
    *     path="/api/user/get/{id}",
    *     tags={"Gestión de Usuarios"},
    *     summary="Obtiene los datos de un Usuario por su ID.",
    *     description="Obtiene los datos de un Usuario por su ID. Se necesita estar autenticado para realizar la solicitud. El Rol Supervisor Tributario tiene acceso a este endpoint.",
    *     operationId="showUser",
    *     security={{"bearer":{}}},
    *      @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Datos encontrados exitosamente.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
    *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
    *      @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
    public function show($id): JsonResponse
    {
        try {

            $user = $this->userRepository->getByID($id);

            if(is_null($user))
                return $this->responseError(null, "EL usuario no ha sido encontrado.", Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($user, 'Datos encontrados exitosamente.');

         } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : UserController ::: Metodo : show");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }


    /**
     * @OA\PUT(
     *     path="/api/user/me/update",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="Actualiza los datos del usuario autenticado",
     *     description="Actualiza los datos del usuario autenticado",
     *     operationId="updatemeUser",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *             @OA\Property(property="name", type="string", example="Marco"),
     *             @OA\Property(property="surnames", type="string", example="Estrada López"),
     *             @OA\Property(property="request_new_password", type="boolean", example=false),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Respuesta de Actualización Exitosa.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
     *      @OA\Response(response=400, description="Error en los datos que se necesita para la solicitud.", @OA\JsonContent(ref="#/components/schemas/ResponseUser400UpdatemeSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function updateme(UserUpdatemeRequest $request): JsonResponse
    {
        try {

            $data = $request->all();

            $obj = $this->userRepository->updateme($data);

            if (is_null($obj))
                return $this->responseError(null, 'EL Usuario no ha sido encontrado..', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($obj, 'EL Usuario ha sido actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : UserController ::: Metodo : updateme");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/user/me/update/password",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="Actualiza la contraseña del usuario autenticado",
     *     description="Actualiza la contraseña del usuario autenticado",
     *     operationId="updatemepasswordUser",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *             @OA\Property(property="password", type="string", example="abcd1234"),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Respuesta de Actualización de Contraseña Exitosa.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
     *      @OA\Response(response=400, description="Error en los datos que se necesita para la solicitud.", @OA\JsonContent(ref="#/components/schemas/ResponseUser400UpdatemepasswordSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function updatemepassword(UserUpdatemepasswordRequest $request): JsonResponse
    {
        try {

            $data = $request->all();

            $obj = $this->userRepository->updatemepassword($data);

            if (is_null($obj))
                return $this->responseError(null, 'EL Usuario no ha sido encontrado..', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($obj, 'EL Usuario ha actualizado su contraseña.');
        } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : UserController ::: Metodo : updatemepassword");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/user/update",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="Actualiza los datos del usuario",
     *     description="Actualiza los datos del usuario. Solo es Supervisor Tributario tiene acceso a realizar esta acción.",
     *     operationId="updateUser",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *             @OA\Property(property="id", type="integer", example=0),
     *             @OA\Property(property="name", type="string", example="Marco"),
     *             @OA\Property(property="surnames", type="string", example="Estrada López"),
     *             @OA\Property(property="request_new_password", type="boolean", example=false),
     *             @OA\Property(property="roles", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="role_id", type="integer", example=1),
     *                 ),
     *             ),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Respuesta de Actualización Exitosa.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
     *      @OA\Response(response=400, description="Error en los datos que se necesita para la solicitud.", @OA\JsonContent(ref="#/components/schemas/ResponseUser400UpdateSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        try {

            $data = $request->all();

            $obj = $this->userRepository->update($data);

            if (is_null($obj))
                return $this->responseError(null, 'EL Usuario no ha sido encontrado..', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($obj, 'EL Usuario ha sido actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : UserController ::: Metodo : updateme");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

    /**
    * @OA\POST(
    *     path="/api/user/paginate",
    *     tags={"Gestión de Usuarios"},
    *     summary="Listado de Usuarios paginados y búsqueda",
    *     description="Obtiene el Listado de Usuarios paginados y con búsqueda por criterios.Solo el supervisor Tributario tiene acceso a esta acción",
    *     operationId="paginateUser",
    *     security={{"bearer":{}}},
    *     @OA\RequestBody(
    *          @OA\JsonContent(
    *              type="object",
    *             @OA\Property(property="name", type="integer", example=""),
    *             @OA\Property(property="surnames", type="integer", example=""),
    *             @OA\Property(property="email", type="integer", example=""),
    *             @OA\Property(property="per_page", type="integer", example="1"),
    *             @OA\Property(property="current_page", type="integer", example="1"),
    *          ),
    *      ),
    *     @OA\Response(response=200,description="Listado como  un Arreglo de elementos Paginados.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200PaginateSchema")),
    *     @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *     @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
    *     @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *     @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
    public function paginate(Request $request){

        try {

            $options = $request->all();
            $data = $this->userRepository->getPaginatedData($options);
            return $this->responseSuccess($data, 'Datos Obtenidos Exitosamente.');

        } catch (\Exception $e) {

            Log::error("ERROR :::  Controlador : UserController ::: Metodo : paginate");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());

        }

    }


    /**
     * @OA\PUT(
     *     path="/api/user/changestatus",
     *     tags={"Gestión de Usuarios"},
     *     summary="Cambia el estado de un Usuario.",
     *     description="Cambia el estado un Usuario de activo/inactivo. Solo el Supervisor Tributario tiene acceso a esta acción.",
     *     operationId="changestatusUser",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Respuesta de Cambio de Estado Exitoso.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
     *      @OA\Response(response=400, description="Error en los datos que se necesita para la solicitud.", @OA\JsonContent(ref="#/components/schemas/ResponseUser400ChangeStatusSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function changestatus(UserChangeStatusRequest $request): JsonResponse
    {

        try {

            $data = $request->all();

            $obj = $this->userRepository->changestatus($data['id']);

            if (is_null($obj))
                return $this->responseError(null, 'EL Usuario no ha sido encontrado.', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($obj, 'El estado ha sido actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : UserController ::: Metodo : changestatus");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }

    }

}
