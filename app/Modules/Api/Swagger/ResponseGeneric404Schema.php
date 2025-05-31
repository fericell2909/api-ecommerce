<?php
namespace App\Modules\Api\Swagger;
/**
 * @OA\Schema(
 *     schema="ResponseGeneric404Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="La solicitud no ha sido encontrada en el servidor.", description="Indica que no se encontro la solicitud en el servidor."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(property="data", type="null", example=null),
 * )
 */

class ResponseGeneric404Schema {

}
