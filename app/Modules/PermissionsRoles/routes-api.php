<?php

use App\Modules\PermissionsRoles\Controller\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('/roles')
	->middleware('auth:api',)
	->group(function () {


    Route::get('/list', [RoleController::class, 'index']);



});

