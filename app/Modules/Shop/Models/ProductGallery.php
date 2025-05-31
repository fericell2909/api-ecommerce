<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\File\Models\File;

class ProductGallery extends Model
{
	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_product_gallery';

	protected $fillable = [
		'id',
		'product_id',
		'file_id',
		'order',
		'main',
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];
	public function file()
    {
    	return $this->hasOne(File::class,'id','file_id');
    }
}
