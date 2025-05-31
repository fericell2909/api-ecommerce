<?php
namespace App\Modules\Auth\Swagger\Response;
/**
 * @OA\Schema(
 *     schema="ResetPassword400Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="El token es inválido"),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="token", type="array",
 *             @OA\Items(type="string", example="Debe ingresaer un token.")
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */


class ResetPassword400Schema {

}
