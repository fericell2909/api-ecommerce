<?php
namespace App\Modules\Api\Swagger;
/**
 * @OA\Schema(
 *     schema="ResponseGeneric500Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ha ocurrido un error en el servidor", description="Mensaje que se obtiene de getMessage de la Excepción."),
 *     @OA\Property(property="errors", type="null"),
 *     @OA\Property(property="data", type="null"),
 *     @OA\Property(property="ex", type="string", example="...", description="Se transforma a string el objeto de la Excepción ocurrida en el servidor")
 * )
 */

class ResponseGeneric500Schema {

}
