<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status';
    //public $translatable = ['name'];
    const ACTIVE = 1;
    const INACTIVE = 2;
    const TABLE = 'status';

    protected $hidden = [
        // 'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function getClassName()
    {
        return 'status';
    }
}
