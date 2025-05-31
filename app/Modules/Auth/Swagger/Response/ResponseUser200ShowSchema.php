<?php

namespace App\Modules\Auth\Swagger\Response;

/**
 * @OA\Schema(
 *     schema="ResponseUser200ShowSchema",
 *     type="object",
 *     description="Respuesta Exitosa.",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Los datos del usuario han sido proporcionados."),
 *     @OA\Property(property="errors", type="null"),
 *     @OA\Property(
 *         property="data",
 *         description="Se devuelve una estructura como la descrita.",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Marco"),
 *         @OA\Property(property="surnames", type="string", example="Estrada Lopez"),
 *         @OA\Property(property="email", type="string", example="fericell2909@gmail.com"),
 *         @OA\Property(property="request_new_password", type="integer", example=1),
 *         @OA\Property(property="status_id", type="integer", example=1),
 *         @OA\Property(
 *             property="status",
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Activo")
 *         ),
 *         @OA\Property(
 *             property="roles",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Analista Tributario")
 *             )
 *         )
 *     )
 * )
 */
class ResponseUser200ShowSchema {

}
