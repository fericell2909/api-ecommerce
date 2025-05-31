<?php

namespace App\Modules\PermissionsRoles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasFactory, HasTranslations;

    protected $table =  'menus';
    public $translatable = ['title'];
    protected $hidden = ['role_id', 'created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'order',
        'icon',
        'color',
        'navLink',
        'externalLink',
        'parent_id',
        'role_id',
        'status_id'
    ];
    public static function getClassName()
    {
        return 'menus';
    }

    public function childrens()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->childrens()->with('children');
    }
}
