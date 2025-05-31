<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRelated extends Model
{
	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_products_related';

	protected $fillable = [
		'created_by',
		'product_id',
		'products_related_id',
		'oreder',
		'type',
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
