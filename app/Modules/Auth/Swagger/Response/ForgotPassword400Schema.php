<?php
namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ForgotPassword400Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error al reiniciar contraseña.", description="Error al reiniciar contraseña."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="email", type="array",
 *             @OA\Items(type="string", example={"El email es requerido","El email no debe ser mayor a 255 caracteres","El email no existe."})
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */

class ForgotPassword400Schema {

}
