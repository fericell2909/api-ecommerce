<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Currency extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    const TABLE = 'currencies';
    protected $table = 'currencies';
    public $translatable = ['name'];

    const PEN = 1;
    const USD = 2;

    protected $hidden = [
        // 'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function getClassName()
    {
        return 'currencies';
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }


    public static function scopeWithActive($query)
    {

        return $query->where('status_id', Status::ACTIVE)->get();
    }


    public function currencies($arr, $rol)
    {
        return Currency::select('id', 'name')->get();
    }
}
