<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthRepository
{
    public function register(array $data): User
    {
        $data = [
            'name' => $data['name'],
            'surnames' => $data['surnames'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ];

        $user = User::create($data);

        return $user;
    }

    public function updatePassword(array $data): bool
    {
        $affected = User::where('id', $data['user_id'])->update(['password' => Hash::make($data['password'])]);

        if ($affected === 1) {
            return true;
        }
        return false;
    }

    public function user($user)
    {
        return User::where('id', $user->id)->with(['file', 'status'])->first();
    }

    public function users($arr, $rol) {
        return User::select('id','name','email')->get();
    }
}
