<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\File\Models\File;

class Producttop extends Model
{
	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_products_top';

	protected $fillable = [
		'created_by',
		'product_id',
		'oreder',
        'file_preview_id',
        'file_later_id',
	];

    public function product() {

		return $this->hasOne(Product::class,'id','product_id')->with('gallery');

    }

    public function preview() {
        return $this->hasOne(File::class,'id','file_preview_id');
    }

    public function later() {

        return $this->hasOne(File::class,'id','file_later_id');
    }

}
