<?php

namespace App\Modules\File\Swagger;

/**
 * @OA\Schema(
 *     schema="ResponseFile400CreateSchema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Ha ocurrido un error de validación.", description="Error en los datos de validación."),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="name", type="array",
 *             @OA\Items(type="string", example={"Por favor, seleccione un archivo","El campo debe ser un archivo.","El tamaño máximo del archivo permitido es de 2 MB.","El nombre del archivo debe ser menor a 250 caracteres."}),
 *         )
 *     ),
 *     @OA\Property(property="data", type="null", example="null")
 * )
 */
class ResponseFile400CreateSchema {

}
