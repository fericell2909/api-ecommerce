<?php

return [
	'config.auth' => [
		'providers' => [
			'users' => [
				'driver' => 'eloquent',
				'model' => App\Modules\Auth\Models\User::class,
			],
		],
	]
];
