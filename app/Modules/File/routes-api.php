<?php

use App\Modules\File\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::prefix('/files')
	->middleware('auth:api',)
	->group(function () {

    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/get/{id}', [FileController::class, 'show']);
    Route::get('/download/{id}', [FileController::class, 'download']);
    Route::delete('/delete/{id}', [FileController::class, 'delete']);

});

