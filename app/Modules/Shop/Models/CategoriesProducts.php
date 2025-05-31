<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class CategoriesProducts extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'ecommerce_categories_products';

    protected $fillable = [
		'created_by',
		'category_id',
		'product_id',
    ];
    protected $appends = ['category_name','category_parent_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function getProductNameAttribute()
    {
        return $this->product->name;
    }

    public function getCategoryNameAttribute($value)
    {
        $name = $this->category->name;
        return $name;
    }

    public function getCategoryParentIdAttribute($value)
    {
        $id = $this->category->parent_id;
        return $id;
    }
}
