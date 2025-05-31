<?php

namespace App\Modules\Api;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{

	public function register()
	{
		require_once __DIR__ . '/helpers.php';
		//$this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
	}

	public function boot()
	{
		RateLimiter::for('api', function (Request $request) {
			return Limit::perMinute(300)->by(optional($request->user())->id ?: $request->ip());
		});
	}
}
