<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseUser400ChangeStatusSchema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ha ocurrido un error de validación.", description="Error en los datos de validación."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="id", type="array",
 *             @OA\Items(type="string", example={"El ID del Usuario es requerido.","El ID del Usuario debe ser un número.","El ID del Usuario no existe."}),
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */
class ResponseUser400ChangeStatusSchema {

}
