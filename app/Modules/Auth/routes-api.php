<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\Auth\AuthController;
use App\Modules\Auth\Controllers\Auth\ForgotController;
use App\Modules\Auth\Controllers\Auth\ResetPasswordController;
use App\Modules\Auth\Controllers\User\UserController;

Route::prefix('/auth')
	->group(function () {
		Route::post('/login', [AuthController::class, 'login']);
		Route::post('/forgot/password', [ForgotController::class, 'forgot']);
		Route::get('/forgot/password/validate/{token}', [ResetPasswordController::class, 'token']);
		Route::put('/forgot/password/change', [ResetPasswordController::class, 'change']);
	});

Route::prefix('/user')
	->middleware('auth:api','role:Supervisor Tributario')
	->group(function () {
        Route::post('/create', [UserController::class, 'create']);
        Route::get('/get/{id}', [UserController::class, 'show']);
        Route::put('/update', [UserController::class, 'update']);
        Route::post('/paginate', [UserController::class, 'paginate']);
        Route::put('/changestatus', [UserController::class, 'changestatus']);
    });

Route::prefix('/user')
	->middleware('auth:api')
	->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::put('/me/update', [UserController::class, 'updateme']);
        Route::put('/me/update/password', [UserController::class, 'updatemepassword']);

    });

Route::prefix('/auth')
	->middleware('auth:api',)
	->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
});
