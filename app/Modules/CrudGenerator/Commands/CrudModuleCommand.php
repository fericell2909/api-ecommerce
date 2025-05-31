<?php

namespace App\Modules\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudModuleCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapimodule
                            {name : The name of the module}
                          ';

    /**SSS
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'module';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/module.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {

        $stub = $this->files->get($this->getStub());

        return $stub;


    }



}
