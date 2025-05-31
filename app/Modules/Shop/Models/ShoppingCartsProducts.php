<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingCartsProducts extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'ecommerce_shopping_carts_products';

    protected $fillable = [
    	'cart_id',
		'product_id',
		'product_quantity',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function products()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}
