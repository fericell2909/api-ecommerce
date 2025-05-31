<?php

namespace App\Modules\PermissionsRoles\Controller;

use App\Http\Controllers\Controller;


use App\Modules\PermissionsRoles\Repositories\RoleRepository;
use Illuminate\Http\Request;

use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use ResponseTrait;

    public $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->middleware('auth:api', ['except' => ['']]);
        $this->repository = $repository;
    }



    /**
    * @OA\GET(
    *     path="/api/roles/list",
    *     tags={"Roles"},
    *     summary="Listado de Roles.",
    *     description="Obtiene el Listado de todos los Roles del sistema.",
    *     operationId="indexRoles",
    *     security={{"bearer":{}}},
    *     @OA\Response(response=200,description="Listado de todos los roles." , @OA\JsonContent(ref="#/components/schemas/ResponseRoles200ListSchema")),
    *     @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *     @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *     @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
    public function index(): JsonResponse
    {
        try {
            $data = $this->repository->getAll();
            return $this->responseSuccess($data, 'Datos Obtenidos Exitosamente.');
        } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : RoleController ::: Metodo : index");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

}
