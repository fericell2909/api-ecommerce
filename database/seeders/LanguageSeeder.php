<?php

namespace Database\Seeders;

use App\Modules\Api\Models\Language;
use Database\Seeders\traits\ClassDetector;
use Illuminate\Database\Seeder;


class LanguageSeeder extends Seeder
{
    use ClassDetector;
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
        $this->classname = 'LanguageSeeder';
    }

    public function run()
    {
        $this->print->writeln("INICIO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));

        if (env('IS_INITIAL')) {
            // creamos los lenguuajes que soportará las tablas

            $language_es = new Language();
            $language_es->alias = 'es';
            $language_es->country_code = 'es';
            $language_es->setTranslations('description', [
                'es' => 'Castellano',
                'en' => 'Spanish',
            ]);

            $language_es->save();

            $language_en = new Language();
            $language_en->alias = 'en';
            $language_en->country_code = 'us';
            $language_en->setTranslations('description', [
                'es' => 'Inglés',
                'en' => 'English',
            ]);

            $language_en->save();
        }


        $this->print->writeln("TERMINO Seeder : " . $this->classname . " :: AMBIENTE --  " . env('APP_ENV'));


        $this->print = null;
    }
}
