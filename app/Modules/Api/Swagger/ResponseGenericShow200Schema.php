<?php

namespace App\Modules\Api\Swagger;

/**
 * @OA\Schema(
 *     schema="ResponseGenericShow200Schema",
 *     type="object",
 *     description="El objeto a buscar por el ID ha sido encontrado exitosamente.",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Respuesta genérica para datos encontrados exitosamente."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(
 *         property="data",
 *         description="El objeto que se devuelve tiene la estructura.",
 *         type="object",
 *         example={
 *             "id": 1,
 *             "attribute": "El atributo",
 *              "atributo2" : 1,
 *              "atributo3" : "01-01-2023",
 *              "user_id" : 1,
 *              "user":  {
 *                "id" : 1,
 *                "name":  "Juan",
 *                "surnames": "Perez",
 *                "email": "example@byg.com"
 *            },
 *             "status_id" : 1,
 *             "status" : {
 *               "id" : 1,
 *               "name":  "Activo"
 *             }
 *         }
 *     ),
 * )
 */
class ResponseGenericShow200Schema {

}
