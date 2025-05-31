<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Auth\Models\User;
use App\Modules\File\Models\File;

class ProductEvaluation extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'ecommerce_products_evaluations';

    protected $fillable = [
    	'product_id',
    	'created_by',
		'score',
		'commentary',
		'name',
        'email',
        'created_at',
        'title_commentary',
        'file_id'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function getNameAttribute($value)
    {

        $name = $this->user->fullName ?? $value;
        if($this->user){
            unset($this->user);
        }
        return $name;
    }
    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function file() {
        return $this->belongsTo(File::class,'file_id');
    }

    public function product() {
        return $this->belongsTo(Product::class,'product_id');
    }
}
