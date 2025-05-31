<?php

namespace App\Modules\Shop\Swagger;

/**
 * @OA\Schema(
 *     schema="ResponseCategory200ShowSchema",
 *     type="object",
 *     description="Respuesta de Obtención de Categoría",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Datos encontrados exitosamente."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(
 *         property="data",
 *         description="Se devuelve una estructura como la descrita.",
 *         type="object",
 *         example={
 *           "id": 1,
 *           "created_by": 1,
 *           "parent_id": null,
 *           "order": 1,
 *           "icon": "fa-icon-alias",
 *           "name": "commodi molestias",
 *           "slug": "tempora-occaecati"
 *         }
 *     ),
 * )
 */
class ResponseCategory200ShowSchema {

}
