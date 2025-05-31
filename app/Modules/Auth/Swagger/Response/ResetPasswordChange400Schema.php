<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResetPasswordChange400Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error al reiniciar contraseña.", description="Error al reiniciar contraseña."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="token", type="array",
 *             @OA\Items(type="string", example="El token es requerido."),
 *         ),
 *         @OA\Property(property="password", type="array",
 *             @OA\Items(type="string", example={"La contraseña es requerida.","El formato del email no es válido.","La contraseña debe tener al menos 8 caracteres.","La contraseña debe tener máximo 16 caracteres."}),
 *         ),
 *         @OA\Property(property="confirm_password", type="array",
 *             @OA\Items(type="string", example={"La nueva contraseña es requerida.","La nueva contraseña debe tener al menos 8 caracteres.","La nueva contraseña debe tener máximo 16 caracteres.", "La confirmación de la contraseña no coincide con la contraseña."}),
 *         ),
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */

class ResetPasswordChange400Schema
{

}
