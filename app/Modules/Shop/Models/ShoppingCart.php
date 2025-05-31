<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingCart extends Model
{
	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_shopping_carts';

	protected $fillable = [
		'owner_id',
		'key',
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];
	public function products()
	{
		$scp_t=(new ShoppingCartsProducts)->getTable();
		return $this->belongsToMany(Product::class,$scp_t,'cart_id')
		->withPivot(
			'cart_id','product_id','product_quantity',
		)
		->select(
			'id','name','slug','sku','price',
		)
		->available();
	}
}
