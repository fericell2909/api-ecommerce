<?php

namespace App\Modules\PermissionsRoles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as PermisionSpatie;
use Spatie\Translatable\HasTranslations;

class Permission extends PermisionSpatie
{
    use HasTranslations;

    protected $table =  'permissions';
    public $translatable = ['name'];

    public static function getClassName()
    {
        $tables = config('permission.table_names');

        return $tables['permissions'];
    }
}
