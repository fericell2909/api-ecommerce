<?php
namespace App\Modules\Api\Swagger;
/**
 * @OA\Schema(
 *     schema="ResponseGeneric401Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Acceso no Autenticado.", description="Indica que no puede acceder a la solicitud ya que no esta autenticado."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(property="data", type="null", example=null),
 * )
 */

class ResponseGeneric401Schema {

}
