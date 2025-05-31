<?php

return [
	'requires' => ['Auth'],
	'config.permission' => [
		'models' =>  [
            'permission' => App\Modules\PermissionsRoles\Models\Permission::class,
            'role' => App\Modules\PermissionsRoles\Models\Role::class,
        ]
	]
];

