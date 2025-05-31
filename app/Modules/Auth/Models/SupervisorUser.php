<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table =  'supervisor_has_users';

    const TABLE = 'supervisor_has_users';

    protected $fillable = [
        'supervisor_id',
        'user_id',
        'status_id',
    ];


    public static function register_user_in_supervisor($user_id, $supervisor_id)
    {

        SupervisorUser::create([
            'user_id' => $user_id,
            'supervisor_id' => $supervisor_id,
        ]);
    }
}
