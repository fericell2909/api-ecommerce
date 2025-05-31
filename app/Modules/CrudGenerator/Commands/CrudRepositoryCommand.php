<?php

namespace App\Modules\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapirepository
                            {name : The name of the Repository}
                            {--repository-namespace= : The namespace of the Repository.}
                            {--repository-name= : The name of the Repository.}
                            {--model-namespace= : The namespace of the Repository.}
                            {--model-name= : The name of the Repository.}
                          ';

    /**SSS
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/repository.stub';
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


        $modelName = $this->option('model-name');
        $modelNamespace = $this->option('model-namespace');

        $repositoryName = $this->option('repository-name');

        $repositoryNamespace = $this->option('repository-namespace'); 
    
        return $this->replaceNamespace($stub, $name)
            ->replaceModelName($stub, $modelName)
            ->replaceRepositoryName($stub, $repositoryName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceRepositoryNamespace($stub, $repositoryNamespace)
            ->replaceClass($stub, $name);
        

    }

    protected function replaceModelName(&$stub, $modelName)
    {
        $stub = str_replace('{{modelName}}', $modelName, $stub);

        return $this;
    }

    protected function replaceRepositoryName(&$stub, $repositoryName)
    {
        $stub = str_replace('{{repositoryName}}', $repositoryName, $stub);

        return $this;
    }


    /**
     * Replace the modelNamespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelNamespace
     *
     * @return $this
     */
    protected function replaceModelNamespace(&$stub, $modelNamespace)
    {
        $stub = str_replace('{{modelNamespace}}', $modelNamespace, $stub);

        return $this;
    }

    protected function replaceRepositoryNamespace(&$stub, $repositoryNamespace)
    {
        $stub = str_replace('{{repositoryNamespace}}', $repositoryNamespace, $stub);

        return $this;
    }

}
