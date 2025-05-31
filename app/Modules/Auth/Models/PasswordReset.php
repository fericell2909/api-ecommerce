<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Auth\Models\User;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table =  'password_resets';


    public static function getClassName()
    {
        return 'password_resets';
    }

    protected $fillable = [
        'id',
        'user_id',
        'token',
        'user_agent',
        'expiration_date',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
