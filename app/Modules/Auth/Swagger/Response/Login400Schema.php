<?php
namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseLogin400Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error accediendo a la cuenta.", description="Puede venir mensaje de Error accediendo a la cuenta o correo electrónico sin verificar."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="email", type="array",
 *             @OA\Items(type="string", example={"El e-mail debe tener al menos 8 caracteres.","El e-mail no debe ser mayor a 255 caracteres.","El formato del e-mail no es válido.","El e-mail es requerido."})
 *         ),
 *         @OA\Property(property="password", type="array",
 *             @OA\Items(type="string", example={"La contraseña es requerida."})
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */

class Login400Schema {

}
