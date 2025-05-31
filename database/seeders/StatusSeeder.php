<?php

namespace Database\Seeders;

use App\Modules\Api\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
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
        $this->classname = 'StatusSeeder';
    }

    public function run()
    {
        $this->print->writeln("INICIO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));

        if (env('IS_INITIAL')) {
            // creamos los lenguuajes que soportará las tablas

            $status_act = new Status();
            $status_act->color = 'primary';
            $status_act->name = 'Activo';

            // $status_act->setTranslations('name', [
            //     'es' => 'Activo',
            //     'en' => 'Active',
            // ]);

            $status_act->save();


            $status_inact = new Status();
            $status_inact->color = 'danger';
            $status_inact->name = 'Inactivo';
            // $status_inact->setTranslations('name', [
            //     'es' => 'Inactivo',
            //     'en' => 'Inactive',
            // ]);

            $status_inact->save();

            // actualizar estados de tablas adicionales.

        }


        $this->print->writeln("TERMINO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));


        $this->print = null;
    }
}
