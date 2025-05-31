<?php

namespace App\Modules\PermissionsRoles\Repositories;

use App\Modules\PermissionsRoles\Models\Role;
use App\Modules\Auth\Models\User;

class RoleRepository
{

    public function getAll()
    {
        return Role::select('id', 'name','code','guard_name','status_id')->with(['status' => function ($query) {
                    $query->select('id', 'name');
                }])->get();
    }

}
