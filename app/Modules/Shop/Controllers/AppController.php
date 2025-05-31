<?php

namespace App\Modules\Shop\Controllers;

use App\Http\Controllers\Controller;

class AppController extends Controller
{

	function __construct(){
		# code...
	}


	protected function serverError(\Exception $e){
		$code = $e->getCode() == NULL ? 500 : $e->getCode();

		return response([
			'status' => 'fails',
			'message' => $e->getMessage(),
			'code' =>  $code,
			'raw' => $e
		], $code);
	}

}
