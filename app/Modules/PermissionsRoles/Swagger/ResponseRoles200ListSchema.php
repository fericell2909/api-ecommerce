<?php
namespace App\Modules\PermissionsRoles\Swagger;


/**
 * @OA\Schema(
 *     schema="ResponseRoles200ListSchema",
 *     type="object",
 *     description="Respuesta de los roles encontrados",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Datos Obtenidos Exitosamente."),
 *     @OA\Property(property="data", type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=3),
 *                 @OA\Property(property="name", type="string", example="Analista Tributario"),
 *                 @OA\Property(property="code", type="string", example="AT"),
 *                 @OA\Property(property="guard_name", type="string", example="api"),
 *                 @OA\Property(property="status_id", type="integer", example=1),
 *                 @OA\Property(property="status", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Activo")
 *                 )
 *             )
 *     )
 * )
 */
class ResponseRoles200ListSchema {

}
