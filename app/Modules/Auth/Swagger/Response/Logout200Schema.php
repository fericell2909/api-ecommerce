<?php
namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseLogout200Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Cierre sesión exitosamente", description="Mensaje de respuesta"),
 *     @OA\Property(property="errors", type="null", example="null"),
 *    @OA\Property(property="data", type="null", example="null")
 * )
 */

class Logout200Schema {

}
