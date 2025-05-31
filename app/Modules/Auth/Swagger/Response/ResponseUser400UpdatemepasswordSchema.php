<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseUser400UpdatemepasswordSchema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ha ocurrido un error de validación.", description="Error en los datos de validación."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="password", type="array",
 *             @OA\Items(type="string", example={"La contraseña es requerida.","La contraseña debe tener al menos 8 caracteres.","La contraseña no debe ser mayor a 16 caracteres.","La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial."}),
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */
class ResponseUser400UpdatemepasswordSchema {

}
