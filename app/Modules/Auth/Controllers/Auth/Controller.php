<?php

namespace App\Modules\Auth\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
	 * @OA\Info(
	 *     description="ECOMMERCE API Documentación",
	 *     version="1.0.0",
	 *     title="ECOMMERCE API Documentación",
	 *     @OA\Contact(
	 *         email="fericell2909@gmail.com"
	 *     ),
	 *     @OA\License(
	 *         name="GPL2",
	 *         url="https://devsenv.com"
	 *     )
	 * )
	 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
