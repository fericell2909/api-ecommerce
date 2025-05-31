<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseLogin200Schema",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Acceso Correcto"),
 *     @OA\Property(property="errors", type="null", example="null"),
 *     @OA\Property(property="data", type="object",
 *         @OA\Property(property="token_type", type="string", example="Bearer", description="Tipo de Token"),
 *         @OA\Property(property="accessToken", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9", description="Token de Acceso"),
 *         @OA\Property(property="expires_in", type="integer", example=3600, description="Valor Expresado en segundos"),
 *         @OA\Property(property="id", type="integer", example=1, description="ID usuario logueado."),
 *         @OA\Property(property="email", type="string", example="fericell2909@gmail.com", description="Correo Electrónico de usuario autenticado"),
 *         @OA\Property(property="name", type="string", example="Marco", description="Nombres de usuario autenticado"),
 *         @OA\Property(property="surnames", type="string", example="Estrada López", description="Apellidos de usuario autenticado"),
 *         @OA\Property(property="language", type="string", example="es", description="Lenguaje de usuario autenticado"),
 *         @OA\Property(
 *             property="uroles",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="code", type="string", example="ST", description="Código de Rol"),
 *                 @OA\Property(property="name", type="string", example="Supervisor Tributario", description="Nombre del Rol"),
 *             ),
 *             description="Roles asociados al usuario authenticado"
 *         ),
 *         @OA\Property(
 *             property="roles",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="code", type="string", example="AT", description="Código de Rol"),
 *                 @OA\Property(property="name", type="string", example="Analista Tributario", description="Nombre del Rol"),
 *             ),
 *             description="Listado de Roles que se encuentran en el sistema."
 *         )
 *     )
 * )
 */
class Login200Schema {

}
