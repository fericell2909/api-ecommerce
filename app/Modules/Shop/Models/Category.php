<?php

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
	use SoftDeletes;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'ecommerce_categories';

    protected $fillable = [
        'created_by',
        'parent_id',
        'order',
        'icon',
        'name',
        'slug'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function children()
    {
        return $this->hasMany(Category::class,'parent_id','id');
    }

    public function productsByCategory() {
        return $this->hasMany(CategoriesProducts::class,'category_id','id');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function parent()
    {
        return $this->hasOne(Category::class,'id','parent_id');
    }

    public function scopeOptionsCascade($query)
    {
        return $query->select([
            'id',
            'id as value',
            'name as label',
            'order',
        ])
        ->orderBy('order','asc')
        ->whereNull('parent_id')
        ->with(['children2']);
    }

    public function children2()
    {
        return $this->hasMany(Category::class,'parent_id','id')
            ->orderBy('order','asc')
            ->with('children')
            ->select([
                'id',
                'id as value',
                'name as label',
                'order',
                'parent_id',
            ]);
    }

}
