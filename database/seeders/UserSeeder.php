<?php

namespace Database\Seeders;

use App\Modules\PermissionsRoles\Models\Role as ModelsRole;
use App\Modules\Api\Models\Status;
use App\Modules\Auth\Factories\UserFactory;
use App\Modules\Auth\Models\SupervisorUser;
use App\Modules\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

use function PHPSTORM_META\map;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $print;
    protected $classname;
    public function __construct()
    {
        $this->print = new \Symfony\Component\Console\Output\ConsoleOutput();
        $this->classname = 'RoleSeeder';
    }

    public function run()
    {


        $this->print->writeln("INICIO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));

        if (env('IS_INITIAL')) {

            $data = [
                        [
                            'name' => 'Marco',
                            'surnames' => 'Estrada López',
                            'email' => 'fericell2909@gmail.com',
                            'request_new_password' => true,
                            'password' => Hash::make(env('INITIAL_PASS')),
                            'status_id' => Status::ACTIVE
                        ]
                    ];


            $role_ecommerce_admin = ModelsRole::where('name', env('ES_NAME_ROL_ECOMMERCE_ADMIN'))->first();
            $role_ecommerce_user = ModelsRole::where('name', env('ES_NAME_ROL_ECOMMERCE_USER'))->first();

            foreach ($data as $user) {

                $new_user = User::create([
                    'name' => $user['name'],
                    'surnames' => $user['surnames'],
                    'email' => $user['email'],
                    'request_new_password' => $user['request_new_password'],
                    'password' => $user['password'],
                    'status_id' => $user['status_id'],
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);

                $new_user->assignRole($role_ecommerce_admin);

                event(new \App\Modules\Auth\Events\UserCreated($new_user,$user['password']));

            }

            DB::statement("UPDATE `roles` set status_id = 1;");

        }

        $this->print->writeln("TERMINO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));
    }
}
