<?php

namespace App\Modules\Auth\Models;

use App\Modules\Api\Models\Status;
use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table =  'users';
    CONST TABLE='users';
    public static function getClassName()
    {
        return 'users';
    }
    protected $fillable = [
        'name',
        'surnames',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'request_new_password',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        //'email_verified_at',
        'updated_at',
        'file_id',
        // 'status_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function roles()
    {
        $relation = $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            PermissionRegistrar::$pivotRole
        );

        return $relation;
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    // public function companies()
    // {
    //     return $this->belongsToMany(Company::class, CompanyUser::getClassName(), 'user_id', 'company_id');
    // }

    public function scopeWhereLike($query, $column, $value)
    {
        return $query->where($column, 'like', '%' . $value . '%');
    }

    public function scopeOrWhereLike($query, $column, $value)
    {
        return $query->orWhere($column, 'like', '%' . $value . '%');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

}
