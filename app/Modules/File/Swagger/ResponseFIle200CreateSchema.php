<?php

namespace App\Modules\File\Swagger;

/**
 * @OA\Schema(
 *     schema="ResponseFile200CreateSchema",
 *     type="object",
 *     description="Respuesta de subida de Archivo Exitoso.",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="El archivo ha sido subido exitosamente."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(
 *         property="data",
 *         description="Se devuelve una estructura como la descrita.",
 *         type="object",
 *         example={
 *           "id": 1,
 *           "nombre_original": "prueba_de_carga.xlsx",
 *           "tamanio": 8423,
 *           "extension": "xlsx",
 *           "tipo": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
 *           "hash": "3f46izaxxmfhS0CaH2xjlvgRy",
 *           "status_id": 1,
 *           "user_id": 1,
 *           "user": {
 *             "id": 1,
 *             "name": "Marco",
 *             "surnames": "Estrada López",
 *             "email": "fericell2909@gmail.com"
 *           },
 *           "status": {
 *            "id": 1,
 *            "name": "Activo"
 *           }
 *         }
 *     ),
 * )
 */
class ResponseFile200CreateSchema {

}
