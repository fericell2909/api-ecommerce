<?php

namespace App\Modules\Shop\Swagger;

/**
 * @OA\Schema(
 *     schema="ResponseProduct200ShowSchema",
 *     type="object",
 *     description="Respuesta de Obtención de un Producto",
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
 *           "sku": "VORTXS9WG5",
 *           "name": "quia sequi delectus",
 *           "slug": "dignissimos-magni-excepturi",
 *           "description": "Descripcion del Producto",
 *           "short_description": "descripcion corta del producto",
 *           "total_sales": 406,
 *           "unit": "kg",
 *           "price": 69,
 *           "quantity": 5
 *         }
 *     ),
 * )
 */
class ResponseProduct200ShowSchema {

}
