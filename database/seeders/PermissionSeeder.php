<?php

namespace Database\Seeders;

use App\Modules\PermissionsRoles\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class PermissionSeeder extends Seeder
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
        $this->classname = 'PermissionSeeder';
    }

    public function run()
    {
        $this->print->writeln("INICIO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));

        if (env('IS_INITIAL')) {
            // creamos los roles
            $permissions = [
                ['es' => 'crear-administrador', 'en' => 'create-administrator'],
            ];
            foreach ($permissions as $key) {

                $permission = new Permission();

                $permission->setTranslations('name', [
                    'es' => $key['es'],
                    'en' => $key['en'],
                ]);

                $permission->save();
            }
        }


        $this->print->writeln("TERMINO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));


        $this->print = null;
    }
}
