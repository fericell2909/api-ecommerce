<?php
namespace App\Modules\Auth\Swagger\Response;


/**
 * @OA\Schema(
 *     schema="ResponseUser200PaginateSchema",
 *     type="object",
 *     description="Respuesta de los datos encontrados paginados",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Datos Obtenidos Exitosamente."),
 *     @OA\Property(property="errors", type="null", example=null),
 *     @OA\Property(
 *         property="data",
 *         description="El objeto que se devuelve tiene la estructura.",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="data", type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="integer", example="Marco"),
 *                 @OA\Property(property="surnames", type="string", example="Estrada López"),
 *                 @OA\Property(property="emmail", type="string", example="fericell2909@gmail.com"),
 *                 @OA\Property(property="status_id", type="integer", example=1),
 *                 @OA\Property(property="status", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Activo")
 *                 ),
 *                 @OA\Property(property="roles", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Analista Tributario")
 *                 )
 *             )
 *         ),
 *         @OA\Property(property="first_page_url", type="string", example="http://127.0.0.1:8000/api/user/paginate?per_page=1&current_page=1&page=1"),
 *         @OA\Property(property="from", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=3),
 *         @OA\Property(property="last_page_url", type="string", example="http://127.0.0.1:8000/api/user/paginate?per_page=1&current_page=1&page=3"),
 *         @OA\Property(property="links", type="array",
 *             @OA\Items(
 *                 @OA\Property(property="url", type="string", example=null),
 *                 @OA\Property(property="label", type="string", example="&laquo; Anterior"),
 *                 @OA\Property(property="active", type="boolean", example=false)
 *             )
 *         ),
 *         @OA\Property(property="next_page_url", type="string", example="http://127.0.0.1:8000/api/user/paginate?per_page=1&current_page=1&page=2"),
 *         @OA\Property(property="path", type="string", example="http://127.0.0.1:8000/api/user/paginate"),
 *         @OA\Property(property="per_page", type="integer", example=1),
 *         @OA\Property(property="prev_page_url", type="null", example=null),
 *         @OA\Property(property="to", type="integer", example=1),
 *         @OA\Property(property="total", type="integer", example=3)
 *     )
 * )
 */
class ResponseUser200PaginateSchema {

}
