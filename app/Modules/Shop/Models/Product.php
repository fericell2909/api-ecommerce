<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Shop\Models\ProductGallery;

class Product extends Model
{
	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_products';

	protected $fillable = [
		'created_by',
		'sku',
		'name',
		'slug',
		'description',
		'short_description',
		'total_sales',
		'unit',
		'price',
		'discount',
		'quantity',
		'active',
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	public function getPriceAttribute($value)
    {
    	// aqui se peude establecer el tipo de valor
        return (int) $value;
    }

	public function gallery()
	{
		return $this->hasMany(ProductGallery::class)->orderBy('order', 'ASC')->with(['file']);
	}

	public function category()
	{
		return $this->belongsToMany(Category::class,(new CategoriesProducts)->getTable())
		->withPivot(
			'category_id',
			'product_id',
		);
	}



	public function scopeAvailable($query)
	{
		return $query->where([
			['active','=',1],
			['quantity','>',0],
		]);
	}

    public function relateds()
	{
		return $this->belongsToMany(Product::class,(new ProductRelated)->getTable(),'product_id','products_related_id')->with(['gallery']);

	}
}
