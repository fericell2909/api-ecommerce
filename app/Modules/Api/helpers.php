<?php

if (!function_exists('apires')) {
	function apires($data = null)
	{
		return new \App\Modules\Api\ApiResponse($data);
	}
}

if (!function_exists('apierror')) {
	function apierror($error, $errorCode = null)
	{
		$apiRes = new \App\Modules\Api\ApiResponse();
		return $apiRes->error($error, $errorCode);
	}
}
