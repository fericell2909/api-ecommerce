<?php

use App\Modules\Shop\Controllers\Admin\ProductController;
use App\Modules\Shop\Controllers\Admin\ProducttopController;
use App\Modules\Shop\Controllers\Admin\CategoryController;

use App\Modules\Shop\Controllers\Overt\CategoryController as CategoryPublicController;
use App\Modules\Shop\Controllers\Overt\ProductPublicController;

use App\Modules\Shop\Controllers\Overt\ShoppingCartController;

use Illuminate\Support\Facades\Route;

Route::prefix('/shop/admin')
	->middleware('auth:api',)
	->group(function () {

    Route::post('/product', [ProductController::class, 'create']);
    Route::post('/product/active', [ProductController::class, 'active']);
	Route::get('/product/list', [ProductController::class, 'list']);
	Route::get('/product/{id}', [ProductController::class, 'show']);
	Route::put('/product/{id}', [ProductController::class, 'update']);
	Route::delete('/product/{id}', [ProductController::class, 'delete']);
	Route::post('/product/{id}/relate', [ProductController::class, 'syncList']);


    Route::get('/producttop/list', [ProducttopController::class, 'list']);
    Route::get('/producttop/listclient', [ProducttopController::class, 'listclient']);
    Route::get('/producttop/show', [ProducttopController::class, 'show']);
    Route::put('/producttop/updateorder', [ProducttopController::class, 'updateorder']);
    Route::post('/producttop/select', [ProducttopController::class, 'select']);

    Route::post('/categories', [CategoryController::class, 'create']);
	Route::get('/categories', [CategoryController::class, 'list']);
    Route::get('/categories/main', [CategoryController::class, 'listMain']);
	Route::get('/categories/{id}', [CategoryController::class, 'show']);
	Route::put('/categories/{id}', [CategoryController::class, 'update']);
	Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
    Route::get('/category/options', [CategoryController::class, 'options']);
    Route::get('/category/selectoptions', [CategoryController::class, 'selectoptions']);
    Route::post('/category/product', [CategoryController::class, 'product']);

});


Route::prefix('/shop')
	->middleware('auth:api',)
    ->group(function () {

        Route::post('shopping/cart', 'ShoppingCartController@cart');

    }
);

Route::prefix('/shop')
	->group(function () {
		// Category
		Route::get('/categories', [CategoryPublicController::class, 'list']);
		Route::get('/categories/{slug}', [CategoryPublicController::class, 'show']);


		// Product
		Route::get('/product/list', [ProductPublicController::class, 'list']);
		Route::get('/product/{param}/{value}', [ProductPublicController::class, 'show']);

	}
);



