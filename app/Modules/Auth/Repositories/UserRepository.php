<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Models\SupervisorUser;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Modules\PermissionsRoles\Models\Role as ModelsRole;
use Illuminate\Support\Facades\Auth;
class UserRepository
{

    public User | null $user;

    public function __construct()
    {
        $this->user = Auth::guard()->user();
    }

    public function register(array $data, string $pass): User|null
    {

        $aux_user = [
            'name' => $data['name'],
            'surnames' => $data['surnames'],
            'email' => $data['email'],
            'password' => Hash::make($pass),
            'request_new_password' => true,
            'remember_token' => Str::random(10),
            'status_id' => 1,
            'file_id' => 0,
            'email_verified_at' => now()
        ];

        $user = User::create($aux_user);

        foreach ($data['roles'] as $role) {

            $rol = ModelsRole::where('id',$role['role_id'])->first();

            $user->assignRole($rol);

        }

        SupervisorUser::register_user_in_supervisor($user->id,$this->user->id);

        return $this->getByID($user->id);

    }

    public function getByID(int $id): User|null
    {

        return User::select('id','name','surnames','email','request_new_password','status_id')->with(['status' => function ($query) {
                    $query->select('id', 'name');
                },'roles' => function ($query)  {
                    $query->select('id', 'name');
                }])->find($id);

    }

    public function updatePassword(array $data): bool
    {
        $affected = User::where('id', $data['user_id'])->update(['password' => Hash::make($data['password'])]);

        if ($affected === 1) {
            return true;
        }
        return false;
    }

    public function user(): User|null
    {
        return $this->getByID($this->user->id);
    }

    public function updateme(array $data): User|null
    {

        $object = User::find($this->user->id);

        if (is_null($object)) {
            return null;
        }

        $object->update(['name' => $data['name'],'surnames' => $data['surnames'],'request_new_password' => $data['request_new_password'] == true ? true : false]);

        return $this->getByID($object->id);

    }

    public function updatemepassword(array $data): User|null
    {

        $object = User::find($this->user->id);

        if (is_null($object)) {
            return null;
        }

        $pass = Hash::make($data['password']);

        $object->update(['password' => $pass,'request_new_password' => false]);

        return $this->getByID($object->id);

    }

    public function update(array $data): User|null
    {

        $object = User::find($data['id']);

        if (is_null($object)) {
            return null;
        }

        $object->update(['name' => $data['name'],'surnames' => $data['surnames'],'request_new_password' => $data['request_new_password'] == true ? true : false]);


        $object->syncRoles($data['roles']);

        return $this->getByID($object->id);

    }

    public function users($arr, $rol) {
        return User::select('id','name','email')->get();
    }

    public function getPaginatedData(array $opts)
    {
        $opts = filterOptionsArray($opts, [
			'per_page' => ['int', env('PAGINATION_RECORDS')],
			'current_page' => ['int', 1],
			'name' => 'string',
            'surnames' => 'string',
            'email' => 'string',
            'status_id' => ['int', -1],
			'id' =>  'int',
		]);

        $opts['current_page'] = max($opts['current_page'], 1);
		$opts['per_page'] = min(max($opts['per_page'], 1), 50);

        $builder = User::select('id','name','surnames','email','request_new_password','status_id');

        if ($opts['id']) {
			$builder->where(function ($query) use ($opts) {
				$query->where(User::TABLE . '.id', $opts['id']);
			});
		}


        if ($opts['name']) {
			$builder->where(User::TABLE . '.name', 'like', '%' . $opts['name'] . '%');
		}

        if ($opts['surnames']) {
            $builder->where(User::TABLE . '.surnames', 'like', '%' . $opts['surnames'] . '%');
        }

        if ($opts['email']) {
            $builder->where(User::TABLE . '.email', 'like', '%' . $opts['email'] . '%');
        }

        if ($opts['status_id'] && $opts['status_id'] !== -1) {
			$builder->where(User::TABLE . '.status_id', '=',   $opts['status_id'] );
		}


        $results = $builder
			->with(['status' => function ($query) {
                    $query->select('id', 'name');
                },'roles' => function ($query)  {
                    $query->select('id', 'name');
                }])
			->orderBy(User::TABLE . '.id', 'desc')
			->paginate($opts['per_page'], ['*'], 'page', $opts['current_page']);

        $results->appends([
			'name' => $opts['name'],
			'surnames' => $opts['surnames'],
            'email' => $opts['email'],
            'status_id' => $opts['status_id'],
            'id' => $opts['id'],
			'per_page' => $opts['per_page'],
			'current_page' => $opts['current_page']
		]);

		return $results;

    }

    public function changestatus(int $id): User|null
    {
        $object = User::find($id);

        if (is_null($object)) {
            return null;
        }

        // 1: Activo 2: Inactivo ==>  Estados generales.
        $object->update(['status_id' => $object->status_id == 1 ? 2 : 1 ]);

        // Finally return the updated object.
        return $this->getByID($object->id);

    }

}
