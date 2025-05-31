<?php

namespace App\Modules\File\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Repositories\FileRepository;
use App\Modules\File\Requests\FileStoreRequest;

use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileController extends Controller
{
    use ResponseTrait;

    public $repository;

    public function __construct(FileRepository $repository)
    {
        $this->middleware('auth:api', ['except' => ['']]);
        $this->repository = $repository;
    }

    /**
     * @OA\POST(
     *     path="/api/files/upload",
     *     tags={"Archivos"},
     *     summary="Subir un Archivo.",
     *     description="Subir un Archivo al repositorio de S3.",
     *     operationId="uploadArchivo",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Archivo a cargar",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="file",
     *                     description="Archivo a cargar",
     *                     type="file",
     *                 ),
     *             ),
     *         ),
     *     ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="El Archivo ha sido subido exitosamente.", @OA\JsonContent(ref="#/components/schemas/ResponseFile200CreateSchema")),
     *      @OA\Response(response=400, description="Error en los datos que se necesita para la solicitud.", @OA\JsonContent(ref="#/components/schemas/ResponseFile400CreateSchema")),
     *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
     *      @OA\Response(response=404, description="No se encontró el recurso en el servidor", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
     *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
     * )
     */
    public function upload(FileStoreRequest $request)//: JsonResponse
    {

        try
        {
            $file = $request->file('file');

            if(Str::length($file->getClientOriginalName()) > 250) {
                $errors = [
                        ['name' => ['El nombre del archivo debe ser menor a 250 caracteres.']]
                ];

                return  $this->responseError($errors, "Ha ocurrido un error de validación.", 400);
            }

            $obj = $this->repository->create($file);

            return $this->responseSuccess($obj, "El Archivo ha sido subido exitosamente.");

        } catch (\Exception $e) {
			Log::error("ERROR :::  Controlador : FileController ::: Metodo : create");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
		}
    }


    /**
    * @OA\GET(
    *     path="/api/files/get/{id}",
    *     tags={"Archivos"},
    *     summary="Obtiene los datos de un Archivo.",
    *     description="Obtiene los datos de un Archivo.",
    *     operationId="showArchivo",
    *     security={{"bearer":{}}},
    *      @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Datos encontrados exitosamente.", @OA\JsonContent(ref="#/components/schemas/ResponseFile200ShowSchema")),
    *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *      @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
    public function show($id): JsonResponse
    {
        try {

            $data = $this->repository->getByIDActive($id);

            if (is_null($data)) {
                return $this->responseError(null, 'El Archivo no ha sido encontrado.', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Datos encontrados exitosamente.');
         } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : FileController ::: Metodo : show");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }


    /**
 * @OA\GET(
 *     path="/api/files/download/{id}",
 *     tags={"Archivos"},
 *     summary="Descargar el Archivo.",
 *     description="Descargar el Archivo.",
 *     operationId="downloadArchivo",
 *     security={{"bearer":{}}},
 *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
 * @OA\Response(
 *     response=200,
 *     description="Respuesta de descarga de archivo",
 *     @OA\MediaType(
 *         mediaType="application/octet-stream",
 *         @OA\Schema(
 *             type="string",
 *             format="binary"
 *         )
 *     ),
 *     @OA\Header(
 *         header="Content-Type",
 *         description="Tipo de contenido del archivo",
 *         @OA\Schema(
 *             type="string",
 *             example="image/jpeg"
 *         )
 *     ),
 *     @OA\Header(
 *         header="Content-Disposition",
 *         description="Disposición del contenido (descarga de archivo)",
 *         @OA\Schema(
 *             type="string",
 *             example="attachment; filename=archivo.jpg"
 *         )
 *     )
 * ),
 *     @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
 *     @OA\Response(response=404, description="El Archivo no ha sido encontrado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
 *     @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
 * )
 */
    public function download($id)
    {
        try {

            $file = $this->repository->getByIDActive($id);

            if(is_null($file)) {
                return $this->responseError(null, 'El Archivo no ha sido encontrado.', Response::HTTP_NOT_FOUND);
            }

            return $this->repository->download($id);

         } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : FileController ::: Metodo : download");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

    /**
    * @OA\DELETE(
    *     path="/api/files/delete/{id}",
    *     tags={"Archivos"},
    *     summary="Eliminar el Archivo.",
    *     description="Eliminar el Archivo del repositorio de S3.",
    *     operationId="deleteArchivo",
    *     security={{"bearer":{}}},
    *      @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="El Archivo ha sido eliminado correctamente.", @OA\JsonContent(ref="#/components/schemas/ResponseFile200DeleteSchema")),
    *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *      @OA\Response(response=404, description="El Archivo no ha sido encontrado. El Archivo no ha sido eliminado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
    public function delete($id): JsonResponse
    {
        try {

            $file = $this->repository->getByIDActive($id);

            if(is_null($file)) {
                return $this->responseError(null, 'El Archivo no ha sido encontrado.', Response::HTTP_NOT_FOUND);
            }

            $result = $this->repository->delete($id);

            if($result) {

                $file = $this->repository->getByID($id);
                return $this->responseSuccess($file, 'El Archivo ha sido eliminado correctamente.');

            }

            return $this->responseError(null, 'El Archivo no ha sido eliminado.', Response::HTTP_NOT_FOUND);

         } catch (\Exception $e) {
            Log::error("ERROR :::  Controlador : FileController ::: Metodo : delete");
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

}
