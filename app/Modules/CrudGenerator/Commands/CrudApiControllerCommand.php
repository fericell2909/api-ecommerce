<?php

namespace App\Modules\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use File;
class CrudApiControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapicontroller
                            {name : The name of the controler.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--model-namespace= : The namespace of the Model.}
                            {--repository-namespace= : The namespace of the Repository.}
                            {--repository-name= : The name of the Repository.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--validations= : Validation rules for the fields.}
                            {--storerequest-namespace= : Namespace of the StoreRequest.}
                            {--storerequest-name= : Name of the StoreRequest.}
                            {--updaterequest-namespace= : Namespace of the UpdateRequest.}
                            {--updaterequest-name= : Name of the UpdateRequest.}
                            {--changestatusrequest-namespace= : Namespace of the ChangeStatusRequest.}
                            {--changestatusrequest-name= : Name of ChangeStatusRequest.}
                            {--path-api= : Path of api to swagger.}
                            {--tags= : Tags of api to swagger.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--fields_from_file= : Fields from a json file.}
                            {--force : Overwrite already existing controller.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api controller.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/api-controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . ($this->option('controller-namespace') ? $this->option('controller-namespace') : 'Http\Controllers');
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        if ($this->option('force')) {
            return false;
        }
        return parent::alreadyExists($rawName);
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

        $crudName = strtolower($this->option('crud-name'));
        $crudNameSingular = Str::singular($crudName);
        $modelName = $this->option('model-name');
        $modelNamespace = $this->option('model-namespace');
        $perPage = intval($this->option('pagination'));
        $validations = rtrim($this->option('validations'), ';');
        $repositoryName = $this->option('repository-name');

        $repositoryNamespace = $this->option('repository-namespace');

        $storeRequestName = $this->option('storerequest-name');
        $storeRequestNameSpace = $this->option('storerequest-namespace');

        $updateRequestNameSpace = $this->option('updaterequest-namespace');
        $updateRequestName = $this->option('updaterequest-name');

        $changestatusRequestNameSpace = $this->option('changestatusrequest-namespace');
        $changestatusRequestName = $this->option('changestatusrequest-name');

        $pathapi = $this->option('path-api');
        $tags = $this->option('tags');

        $fields_from_file = $this->option('fields_from_file');

        $validationRules = '';
        if (trim($validations) != '') {
            $validationRules = "\$this->validate(\$request, [";

            $rules = explode(';', $validations);
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }

                // extract field name and args
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $validationRules .= "\n\t\t\t'$fieldName' => '$rules',";
            }

            $validationRules = substr($validationRules, 0, -1); // lose the last comma
            $validationRules .= "\n\t\t]);";
        }





        return $this->replaceNamespace($stub, $name)
            ->replaceStoreRequestNamespace($stub,$storeRequestNameSpace)
            ->replaceStoreRequestName($stub, $storeRequestName)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceRepositoryName($stub, $repositoryName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceModelNamespaceSegments($stub, $modelNamespace)
            ->replaceRepositoryNamespace($stub, $repositoryNamespace)
            ->replaceValidationRules($stub, $validationRules)
            ->replacePaginationNumber($stub, $perPage)
            ->replacePathApi($stub, $pathapi)
            ->replaceUpdateRequestNamespace($stub, $updateRequestNameSpace)
            ->replaceUpdateRequestName($stub, $updateRequestName)
            ->replaceTags($stub, $tags)
            ->replaceChangeStatusRequestNamespace($stub, $changestatusRequestNameSpace)
            ->replaceChangeStatusRequestName($stub, $changestatusRequestName)
            ->replaceGenericKey($stub,'{{swagger-create-summary}}',$this->getAttributeSwagger($fields_from_file,'swagger-create-summary'))
            ->replaceGenericKey($stub,'{{swagger-create-description}}',$this->getAttributeSwagger($fields_from_file,'swagger-create-description'))
            ->replaceGenericKey($stub,'{{swagger-update-summary}}',$this->getAttributeSwagger($fields_from_file,'swagger-update-summary'))
            ->replaceGenericKey($stub,'{{swagger-update-description}}',$this->getAttributeSwagger($fields_from_file,'swagger-update-description'))
            ->replaceGenericKey($stub,'{{swagger-changestatus-summary}}',$this->getAttributeSwagger($fields_from_file,'swagger-changestatus-summary'))
            ->replaceGenericKey($stub,'{{swagger-changestatus-description}}',$this->getAttributeSwagger($fields_from_file,'swagger-changestatus-description'))
            ->replaceGenericKey($stub,'{{swagger-show-summary}}',$this->getAttributeSwagger($fields_from_file,'swagger-show-summary'))
            ->replaceGenericKey($stub,'{{swagger-show-description}}',$this->getAttributeSwagger($fields_from_file,'swagger-show-description'))
            ->replaceGenericKey($stub,'{{swagger-list-summary}}',$this->getAttributeSwagger($fields_from_file,'swagger-list-summary'))
            ->replaceGenericKey($stub,'{{swagger-list-description}}',$this->getAttributeSwagger($fields_from_file,'swagger-list-description'))
            ->replaceGenericKey($stub,'{{swagger-search-summary}}',$this->getAttributeSwagger($fields_from_file,'swagger-search-summary'))
            ->replaceGenericKey($stub,'{{swagger-search-description}}',$this->getAttributeSwagger($fields_from_file,'swagger-search-description'))
            ->replaceClass($stub, $name);

    }

    protected function getAttributeSwagger($fields_from_file,$key){

        $json = File::get($fields_from_file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'swagger')) {
            return '';
        }

        if(!property_exists($fields->swagger, $key)){
            return '';
        }

        return $fields->swagger->$key;

    }
    protected function replaceGenericKey(&$stub,$key, $swagger)
    {
        $stub = str_replace($key, $swagger, $stub);

        return $this;
    }

    protected function replaceTags(&$stub, $tags)
    {

        // echo "tags ==> " .$tags;
        // echo "array 0 ==> "  .$tags[0] . PHP_EOL;
        // echo "array 1 ==> "  .$tags[1] . PHP_EOL;
        // echo "array 2 ==> "  .$tags[2] . PHP_EOL;

        $aux =  "";

        for($i=0;$i<= strlen($tags);$i++){

            $val = $tags[$i-1];
            // echo "val ===> " .$val;
            if($val !== "\\"){
                $aux .= $val;
            }
        }
        //echo $arrayOriginal;
        //$values = explode('\"', $arrayOriginal);
        //print_r($values);


        // echo "AUX ===> " .$aux;

        $stub = str_replace('{{tags}}', $aux, $stub);

        return $this;
    }

    protected function replacePathApi(&$stub, $pathapi)
    {
        $stub = str_replace('{{path-api}}', $pathapi, $stub);

        return $this;
    }

    protected function replaceCrudName(&$stub, $crudName)
    {
        $stub = str_replace('{{crudName}}', $crudName, $stub);

        return $this;
    }

    /**
     * Replace the crudNameSingular for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudNameSingular
     *
     * @return $this
     */
    protected function replaceCrudNameSingular(&$stub, $crudNameSingular)
    {
        $stub = str_replace('{{crudNameSingular}}', $crudNameSingular, $stub);

        return $this;
    }

    /**
     * Replace the modelName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelName
     *
     * @return $this
     */
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


    protected function replaceStoreRequestNamespace(&$stub, $StoreRequestNamespace)
    {
        $stub = str_replace('{{storeRequestNamespace}}', $StoreRequestNamespace, $stub);

        return $this;
    }

    protected function replaceStoreRequestName(&$stub, $StoreRequestName)
    {
        $stub = str_replace('{{StoreRequestName}}', $StoreRequestName, $stub);

        return $this;
    }

    protected function replaceUpdateRequestNamespace(&$stub, $UpdateRequestNamespace)
    {
        $stub = str_replace('{{updateRequestNamespace}}', $UpdateRequestNamespace, $stub);

        return $this;
    }

    protected function replaceUpdateRequestName(&$stub, $UpdateRequestName)
    {
        $stub = str_replace('{{UpdateRequestName}}', $UpdateRequestName, $stub);

        return $this;
    }


    protected function replaceChangeStatusRequestNamespace(&$stub, $changestatusRequestNameSpace)
    {
        $stub = str_replace('{{changestatusRequestNamespace}}', $changestatusRequestNameSpace, $stub);

        return $this;
    }

    protected function replaceChangeStatusRequestName(&$stub, $changestatusRequestName)
    {
        $stub = str_replace('{{ChangeStatusRequestName}}', $changestatusRequestName, $stub);

        return $this;
    }

    protected function replaceModelNamespaceSegments(&$stub, $modelNamespace)
    {
        $modelSegments = explode('\\', $modelNamespace);
        foreach ($modelSegments as $key => $segment) {
            $stub = str_replace('{{modelNamespace[' . $key . ']}}', $segment, $stub);
        }

        $stub = preg_replace('{{modelNamespace\[\d*\]}}', '', $stub);

        return $this;
    }

    /**
     * Replace the validationRules for the given stub.
     *
     * @param  string  $stub
     * @param  string  $validationRules
     *
     * @return $this
     */
    protected function replaceValidationRules(&$stub, $validationRules)
    {
        $stub = str_replace('{{validationRules}}', $validationRules, $stub);

        return $this;
    }

    /**
     * Replace the pagination placeholder for the given stub
     *
     * @param $stub
     * @param $perPage
     *
     * @return $this
     */
    protected function replacePaginationNumber(&$stub, $perPage)
    {
        $stub = str_replace('{{pagination}}', $perPage, $stub);

        return $this;
    }
}
