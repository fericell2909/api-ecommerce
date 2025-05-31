<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Models\PasswordReset;
use App\Modules\Auth\Repositories\AuthRepository;

class ResetPasswordRepository
{
    /*
    * @var AuthRepository
    */
    private $AuthRepository;

    public function __construct()
    {
        $this->AuthRepository = new AuthRepository();
    }
    private function exists($token): bool
    {

        if (PasswordReset::where('token', $token)->where('nstatus', 1)->count() === 1) {
            return true;
        }
        return false;
    }
    public function isvalidate($token): bool
    {
        if ($this->exists($token)) {

            return true;
        }
        return false;
    }

    public function change($data): bool
    {

        $affected = PasswordReset::where('token', $data['token'])->where('nstatus', 1)->update(['nstatus' => 2]);

        $updated = PasswordReset::where('token', $data['token'])->where('nstatus', 2)->first();

        if ($affected === 1 && $updated) {
            if ($this->AuthRepository->updatePassword(['token' => $data['token'], 'password' => $data['password'], 'user_id' => $updated->user_id])) {
                return true;
            }
        }

        return false;

    }
}
