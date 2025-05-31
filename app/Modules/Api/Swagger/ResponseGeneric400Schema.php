<?php
namespace App\Modules\Api\Swagger;
/**
 * @OA\Schema(
 *     schema="ResponseGeneric400Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error en los datos de validación.", description="Error en los datos de validación."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="atributo", type="array",
 *             @OA\Items(type="string", example="El atributo es requerido."),
 *         ),
 *         @OA\Property(property="atributo1", type="array",
 *             @OA\Items(type="string", example={"El atributo1 es requerida.","El formato del atributo1 no es válido.","El atributo1 debe tener al menos n caracteres.","El atributo1 debe tener máximo n caracteres."}),
 *         ),
 *         @OA\Property(property="atributo2", type="array",
 *             @OA\Items(type="string", example={"El atributo2 es requerida.","el atributo2 debe tener al menos n caracteres.","El atributo2 debe tener máximo n caracteres.", "EL atributo2 no coincide con el atributo1"}),
 *         ),
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */
class ResponseGeneric400Schema {

}
