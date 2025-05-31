<?php

namespace App\Modules\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudRoutesCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapiroutes
                            {name : The name of the routesapi}
                            {--path-api= : The name of path}
                            {--controller-namespace= : The namespace of the Controller.}
                            {--controller-name= : The name of the Controller.}
                          ';

    /**SSS
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new RoutesApi.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'RoutesApi';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/routesapi.stub';
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

        $controllerNamespace = $this->option('controller-namespace');

        $controllerName = $this->option('controller-name');

        $pathapi = $this->option('path-api');


        return $this->replaceControllerNamespace($stub,  $controllerNamespace)->replaceNamePath($stub,$pathapi)->replaceControllerName($stub, $controllerName)->replaceClass($stub,  $name);


    }

    protected function replaceNamePath(&$stub, $controllerNamespace)
    {
        $stub = str_replace('{{names}}', $controllerNamespace, $stub);

        return $this;
    }

    protected function replaceControllerNamespace(&$stub, $controllerNamespace)
    {
        $stub = str_replace('{{controllerNamespace}}', $controllerNamespace, $stub);

        return $this;
    }

    protected function replaceControllerName(&$stub, $controllerName)
    {
        $stub = str_replace('{{controllerName}}', $controllerName, $stub);

        return $this;
    }

}
