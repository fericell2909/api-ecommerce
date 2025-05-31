<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseUser400CreateSchema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ha ocurrido un error de validación.", description="Error en los datos de validación."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="name", type="array",
 *             @OA\Items(type="string", example={"El nombre es requerido.","El nombre debe tener al menos 1 caracter.","El nombre no debe ser mayor a 255 caracteres."}),
 *         ),
 *         @OA\Property(property="surnames", type="array",
 *             @OA\Items(type="string", example={"Los apellidos son requeridos.","Los apellidos deben tener al menos 1 caracter.","Los apellidos no deben ser mayor a 255 caracteres."}),
 *         ),
 *         @OA\Property(property="email", type="array",
 *             @OA\Items(type="string", example={"El email es requerido.","El email debe tener al menos 1 caracter.","El email no debe ser mayor a 255 caracteres.","El email no es válido.","El email ya está en uso."}),
 *         ),
 *         @OA\Property(property="roles.0.role_id", type="array",
 *            @OA\Items(type="string", example={"El rol es requerido.","El rol no existe.","El ID del rol no debe estar repetido."}),
 *         ),
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */
class ResponseUser400CreateSchema {

}

