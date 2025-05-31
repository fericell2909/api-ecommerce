<?php

namespace Database\Seeders;

use App\Modules\Api\Models\Currency;
use App\Modules\Api\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
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
        $this->classname = 'CurrencySeeder';
    }

    public function run()
    {
        $this->print->writeln("INICIO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));

        if (env('IS_INITIAL')) {
            // creamos los lenguuajes que soportará las tablas

            $records = [
                ['id' => Currency::PEN, 'name' => ['es' => 'Nuevos Soles (PEN)', 'en' => 'Nuevos Soles'], 'color' => 'bg-success', 'codigo_sii' => '', 'symbol' => 'PEN', 'status_id' => Status::ACTIVE],
                ['id' => Currency::USD, 'name' => ['es' => 'Dolares Americanos (USD)', 'en' => 'American Dollars'], 'color' => 'bg-warning', 'codigo_sii' => '', 'symbol' => 'USD', 'status_id' => Status::ACTIVE],
            ];

            foreach ($records as $r) {
                $currency = new Currency();
                $currency->id = $r['id'];
                $currency->color = $r['color'];
                $currency->codigo_sii = $r['codigo_sii'];
                $currency->status_id = $r['status_id'];
                $currency->symbol = $r['symbol'];
                $currency->setTranslations('name', [
                    'es' => $r['name']['es'],
                    'en' => $r['name']['en'],
                ]);
                $currency->save();
            }
        }


        $this->print->writeln("TERMINO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));


        $this->print = null;
    }
}
