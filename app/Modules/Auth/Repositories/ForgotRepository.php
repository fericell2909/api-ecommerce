<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Models\PasswordReset;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Str;

class ForgotRepository
{
    public function forgot(array $data): PasswordReset
    {
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            $data = [
                'user_id' => $user->id,
                'token' => Str::random(100),
                'user_agent' => 'none',
                'expiration_date' => null,
                'email' => $user->email,
            ];

            $passwordr = PasswordReset::create($data);

            return PasswordReset::where('id', $passwordr->id)->with('user')->first();
        }

        return new PasswordReset();
    }

    public function exist(PasswordReset $passwordReset)
    {
        if (PasswordReset::where('id', $passwordReset->id)->count() > 0) {
            return true;
        }
        return false;
    }
}
