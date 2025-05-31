<?php
namespace App\Modules\Api\Swagger;

/**
 * @OA\Schema(
 *     schema="ResponseGenericCreate200Schema",
 *     type="object",
 *     description="Respuesta genérica para la creación exitosa de un objeto.",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="El objeto ha sido creado exitosamente."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(
 *         property="data",
 *         description="El objeto que se devuelve tiene la estructura.",
 *         type="object",
 *         example={
 *             "id": 1,
 *             "attribute": "El atributo",
 *             "atributo2" : 1,
 *             "atributo3" : "01-01-2023"
 *         }
 *     ),
 * )
 */
class ResponseGenericCreate200Schema {

}
