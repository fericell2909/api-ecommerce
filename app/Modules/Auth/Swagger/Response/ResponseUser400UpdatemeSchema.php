<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseUser400UpdatemeSchema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ha ocurrido un error de validación.", description="Error en los datos de validación."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="names", type="array",
 *             @OA\Items(type="string", example={"El nombre es requerido.","El nombre debe tener al menos 1 caracter.","El nombre no debe ser mayor a 255 caracteres."}),
 *         ),
 *         @OA\Property(property="surnames", type="array",
 *             @OA\Items(type="string", example={"Los apellidos son requeridos.","Los apellidos deben tener al menos 1 caracter.","Los apellidos no deben ser mayor a 255 caracteres."}),
 *         ),
 *         @OA\Property(property="request_new_password", type="array",
 *             @OA\Items(type="string", example={"El request_new_password es requerido.","El request_new_password debe ser un booleano"}),
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */
class ResponseUser400UpdatemeSchema {

}
