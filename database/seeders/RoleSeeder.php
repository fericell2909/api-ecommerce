<?php

namespace Database\Seeders;

use App\Modules\PermissionsRoles\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\PermissionsRoles\Models\Permission;

class RoleSeeder extends Seeder
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
            //listamos permisos
            $permissions = Permission::get();

            // creamos los roles
            $roles = [
                [
                    'language' => ['es' => env('ES_NAME_ROL_ECOMMERCE_USER'), 'en' => env('EN_NAME_ROL_ANALISTA_TRIBUTARIO')],
                    'code' => 'AT'
                ],
                [
                    'language' => ['es' => env('ES_NAME_ROL_ECOMMERCE_ADMIN'), 'en' => env('EN_NAME_ROL_SUPERVISOR_TRIBUTARIO')],
                    'code' => 'ST'
                ]
            ];

            foreach ($roles as $role) {
                $new_role = new Role();
                $new_role->code = $role['code'];
                $new_role->name = $role['language']['es'];
                // $new_role->setTranslations('name', [
                //     'es' => $role['language']['es'],
                //     'en' => $role['language']['en'],
                // ]);

                $new_role->save();

                //Asignamos por default todos los permisos
                foreach ($permissions as $permission) {
                    $new_role->givePermissionTo($permission);
                }
            }
        }


        $this->print->writeln("TERMINO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));


        $this->print = null;
    }
}
