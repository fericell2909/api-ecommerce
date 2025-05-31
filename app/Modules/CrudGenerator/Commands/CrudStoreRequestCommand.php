<?php

namespace App\Modules\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudStoreRequestCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapistorerequest
                            {name : The name of the StoreRequest}
                            {--storerequest-namespace= : The namespace of the Repository.}
                            {--storerequest-name= : The name of the Repository.}
                            {--validations= : Validation rules for the fields.}
                          ';

    /**SSS
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new StoreRequest.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'StoreRequest';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/storerequest.stub';
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

        $storerequestNamespace = $this->option('storerequest-namespace');

        $storerequestName = $this->option('storerequest-name');
        $validations = $this->option('validations');

        // echo $validations;

        $rules = $this->getRules($validations);
        $messages = $this->getMessages($validations);


        return $this->replaceMessages($stub,$messages)->replaceRules($stub,$rules)->replaceNamespace($stub,  $storerequestNamespace)->replaceClass($stub, $storerequestName);


    }

    protected function replaceRules(&$stub, $rules)
    {
        $stub = str_replace('{{rules}}', $rules, $stub);

        return $this;
    }

    protected function replaceMessages(&$stub, $rules)
    {
        $stub = str_replace('{{messages}}', $rules, $stub);

        return $this;
    }


    private function getRules($validations)
    {
        $rules = "";
        $validations = explode('|||', $validations);

        foreach ($validations as $validation) {
            $validation = explode('¬¬', $validation);

            $aux = explode('@@', $validation[1]);
            $rules .= "'".$validation[0] . "' => '" . $aux[0]."',";
        }

        $rules = rtrim($rules, ',');

        return "return [" . $rules . "];";

    }

    private function getMessages($validations)
    {
        $messages = "";
        $validations = explode('|||', $validations);

        foreach ($validations as $validation) {
            $validation = explode('@@', $validation);

            $aux = explode('||', $validation[1]);
            foreach($aux as $tmp){
                $tmp = explode('#', $tmp);
                $messages .= "'".$tmp[0] . "' => '" . $tmp[1]."',";
            }
        }

        $messages = rtrim($messages, ',');

        return "return [" . $messages . "];";

    }


    /**
     * Replace the modelNamespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelNamespace
     *
     * @return $this
     */


}
