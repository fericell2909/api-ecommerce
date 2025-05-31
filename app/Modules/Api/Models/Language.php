<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasFactory, HasTranslations;

    protected $table =  'languages';
    public $translatable = ['description'];

    protected $hidden = ['created_at', 'updated_at'];
    public static function getClassName()
    {
        return 'languages';
    }
}
