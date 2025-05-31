<?php
namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseLogin403Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error accediendo a la cuenta", description="Puede venir mensaje de Error accediendo a la cuenta o correo electrónico sin verificar."),
 *     @OA\Property(property="errors", type="null", example="null"),
 *    @OA\Property(property="data", type="null", example="null")
 * )
 */

class Login403Schema {

}
