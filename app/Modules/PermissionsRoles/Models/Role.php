<?php

namespace App\Modules\PermissionsRoles\Models;

use App\Modules\Api\Models\Status;
use Spatie\Permission\Models\Role as RoleSpatie;
use Spatie\Translatable\HasTranslations;

class Role extends RoleSpatie
{
    // use HasTranslations;

    protected $table =  'roles';
    public $translatable = [];
    protected $hidden = ['created_at', 'updated_at','pivot','color'];
    public static function getClassName()
    {
        $tables = config('permission.table_names');

        return $tables['roles'];
    }

    // public function menus()
    // {
    //     return $this->hasMany(Menu::class)->where('parent_id', null)->with('children')->orderBy('id', 'asc')->orderBy('order', 'asc');
    // }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}
