<?php

use App\Modules\Modular;
use Illuminate\Support\Facades\Route;

Route::group([
	'prefix' => '/api',
	'middleware' => ['api', 'localization', 'throttle:api'],
], function () {
	Modular::requireAll('/routes-api.php');
});
