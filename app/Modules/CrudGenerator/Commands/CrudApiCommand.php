<?php

namespace App\Modules\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Report\PHP;

class CrudApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapi
                            {name : El nombre del modelo.}
                            {--fields= : Field names for the form & migration.}
                            {--fields_from_file= : Fields from a json file.}
                            {--module-namespace= : Namespace of the module.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--model-namespace= : Namespace of the model.}
                            {--migration-namespace= : Namespace of the migration.}
                            {--repository-namespace= : Namespace of the repository.}
                            {--storerequest-namespace= : Namespace of the store request.}
                            {--pk=id : The name of the primary key.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : The foreign keys for the table.}
                            {--relationships= : The relationships for the model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--route-group= : Prefix of the route group.}
                            {--tags= : Tags of api to swagger.}
                            {--soft-deletes=no : Include soft deletes fields.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate api crud including controller, model & migrations.';

    /** @var string  */
    protected $routeName = '';

    /** @var string  */
    protected $controller = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $name = $this->argument('name');
        $modelName = Str::singular($name);
        $migrationName = Str::plural(Str::snake($name));
        $tableName = $migrationName;
        $repositoryName= Str::singular($name) . 'Repository';
        $storerequestName = Str::singular($name) . 'StoreRequest';
        $updaterequestName = Str::singular($name) . 'UpdateRequest';
        $deleterequestName = Str::singular($name) . 'ChangeStatusRequest';

        // echo $name . PHP_EOL;
        // echo $modelName . PHP_EOL;
        // echo $migrationName . PHP_EOL;
        // echo $tableName . PHP_EOL;


        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . Str::snake($name, '-') : Str::snake($name, '-');
        $perPage = intval($this->option('pagination'));

        $controllerNamespace = ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
        $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : 'Models\\';
        $repositoryNamespace = ($this->option('repository-namespace')) ? trim($this->option('repository-namespace')) . '\\' : 'Models\\';

        $migrationNamespace = ($this->option('migration-namespace')) ? trim($this->option('migration-namespace')) . '\\' : 'Models\\';

        $storeRequestNamespace = ($this->option('storerequest-namespace')) ? trim($this->option('storerequest-namespace')) . '\\' : 'Models\\';

        $moduleNamespace = ($this->option('module-namespace')) ? trim($this->option('module-namespace')) . '\\' : 'Models\\';

        $tags = ($this->option('tags')) ? trim($this->option('tags')) . '\\' : 'Models\\';

        $fields = rtrim($this->option('fields'), ';');

        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }

        //echo $fields;

        $primaryKey = $this->option('pk');
        $fields_from_file = $this->option('fields_from_file');

        $foreignKeys = $this->option('foreign-keys');

        if ($this->option('fields_from_file')) {
             $foreignKeys = $this->processJSONForeignKeys($this->option('fields_from_file'));
        }

        if ($this->option('fields_from_file')) {

            $validationsstore = $this->processJSONValidations($this->option('fields_from_file'),'store');
            $validationsupdate = $this->processJSONValidations($this->option('fields_from_file'),'update');
            $validationsdelete = $this->processJSONValidations($this->option('fields_from_file'),'delete');

        }

        $fieldsArray = explode(';', $fields);
        $fillableArray = [];
        $migrationFields = $fields;

        // foreach ($fieldsArray as $item) {
        //     $spareParts = explode('#', trim($item));
        //     $fillableArray[] = $spareParts[0];
        //     $modifier = !empty($spareParts[2]) ? $spareParts[2] : 'nullable';

        //     // Process migration fields
        //     $migrationFields .= $spareParts[0] . '#' . $spareParts[1];
        //     $migrationFields .= '#' . $modifier;
        //     $migrationFields .= ';';
        // }

        //echo $migrationFields;
        //$commaSeparetedString = implode("', '", $fillableArray);

        $fillable = "[" . $this->getFillables($this->option('fields_from_file')) . "]";

        $indexes = $this->option('indexes');
        $relationships = $this->option('relationships');
        if ($this->option('fields_from_file')) {
            $relationships = $this->processJSONRelationships($this->option('fields_from_file'));
        }

        $softDeletes = $this->option('soft-deletes');

        //
        $this->call('command:crudapimodel', ['name' => $modelNamespace . $modelName, '--fillable' => $fillable, '--table' => $tableName, '--pk' => $primaryKey, '--relationships' => $relationships, '--soft-deletes' => $softDeletes]);

        $this->call('command:crudapimigration', ['name' =>  $tableName, '--migration-namespace' =>  $migrationNamespace , '--schema' => $migrationFields, '--pk' => $primaryKey, '--indexes' => $indexes, '--foreign-keys' => $foreignKeys, '--soft-deletes' => $softDeletes]);

        $this->call('command:crudapirepository', ['name' => $repositoryNamespace . $name . 'Repository',  '--model-name' => $modelName, '--model-namespace' => $modelNamespace,  '--repository-namespace' => $repositoryNamespace , '--repository-name' => $repositoryName]);


        $this->call('command:crudapistorerequest', ['name' => $storeRequestNamespace . $name . 'StoreRequest', '--storerequest-namespace' => $storeRequestNamespace , '--storerequest-name' => $storerequestName  , '--validations' => $validationsstore]);

        $this->call('command:crudapistorerequest', ['name' => $storeRequestNamespace . $name . 'UpdateRequest', '--storerequest-namespace' => $storeRequestNamespace , '--storerequest-name' => $updaterequestName  , '--validations' => $validationsupdate]);
        $this->call('command:crudapistorerequest', ['name' => $storeRequestNamespace . $name . 'ChangeStatusRequest', '--storerequest-namespace' => $storeRequestNamespace , '--storerequest-name' => $deleterequestName  , '--validations' => $validationsdelete]);


        $this->call('command:crudapicontroller', ['name' => $controllerNamespace . $name . 'Controller',
                    '--crud-name' => $name,
                    '--model-name' => $modelName,
                    '--model-namespace' => $modelNamespace,
                    '--pagination' => $perPage,
                    '--repository-namespace' => $repositoryNamespace ,
                    '--repository-name' => $repositoryName,
                    '--storerequest-namespace' => $storeRequestNamespace ,
                    '--storerequest-name' => $storerequestName,
                    '--updaterequest-namespace' => $storeRequestNamespace ,
                    '--updaterequest-name' => $updaterequestName,
                    '--changestatusrequest-namespace' => $storeRequestNamespace ,
                    '--changestatusrequest-name' => $deleterequestName,
                    '--tags' => $tags,
                    '--path-api' => $tableName,
                    '--fields_from_file' => $fields_from_file,
                ]);

        $this->call('command:crudapimodule', ['name' => $moduleNamespace .'module']);

        $this->call('command:crudapiroutes', ['name' => $moduleNamespace .'routes-api', '--path-api' => $tableName ,'--controller-namespace' => $controllerNamespace, '--controller-name' => $name . 'Controller']);

    }

    /**
     * Add routes.
     *
     * @return  array
     */
    protected function addRoutes()
    {
        return ["Route::resource('" . $this->routeName . "', '" . $this->controller . "', ['except' => ['create', 'edit']]);"];
    }


    private function getFillables($file) {
        $json = File::get($file);
        $fields = json_decode($json);

        //var_dump($fields);

        if (! property_exists($fields, 'fields')) {
            return '';
        }

        $fieldString = '';
        foreach ($fields->fields as $field) {
            $aux_temp = explode('@',$field->name);
            $fieldString = $fieldString . "'" . $aux_temp[0] . "',";
        }

        $fieldString = rtrim($fieldString, ',');

        return $fieldString;

    }

    protected function processJSONFields($file)
    {

        $json = File::get($file);
        $fields = json_decode($json);

        //var_dump($fields);

        $fieldsString = '';
        foreach ($fields->fields as $field) {
            if ($field->type == 'select') {
                $fieldsString .= $field->name . '#' . $field->type . '#options=' . json_encode($field->options) . ';';
            } else {
                $fieldsString .= $field->name . '#' . $field->type . ';';
            }
        }

        $fieldsString = rtrim($fieldsString, ';');

        return $fieldsString;
    }

    /**
     * Process the JSON Foreign keys.
     *
     * @param  string $file
     *
     * @return string
     */
    protected function processJSONForeignKeys($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (! property_exists($fields, 'foreign_keys')) {
            return '';
        }

        $foreignKeysString = '';
        foreach ($fields->foreign_keys as $foreign_key) {
            $foreignKeysString .= $foreign_key->column . '#' . $foreign_key->references . '#' . $foreign_key->on;

            if (property_exists($foreign_key, 'onDelete')) {
                $foreignKeysString .= '#' . $foreign_key->onDelete;
            }

            if (property_exists($foreign_key, 'onUpdate')) {
                $foreignKeysString .= '#' . $foreign_key->onUpdate;
            }

            $foreignKeysString .= ',';
        }

        $foreignKeysString = rtrim($foreignKeysString, ',');

        return $foreignKeysString;
    }

    /**
     * Process the JSON Relationships.
     *
     * @param  string $file
     *
     * @return string
     */
    protected function processJSONRelationships($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'relationships')) {
            return '';
        }

        $relationsString = '';
        foreach ($fields->relationships as $relation) {
            $relationsString .= $relation->name . '#' . $relation->type . '#' . $relation->class . ';';
        }

        $relationsString = rtrim($relationsString, ';');

        return $relationsString;
    }

    /**
     * Process the JSON Validations.
     *
     * @param  string $file
     *
     * @return string
     */

    private function getrequest($fields, $key) {

        $validationsString = '';

        foreach ($fields->validations[$key] as $validation) {


            $validationsString .= $validation->field . '¬¬' . $validation->rules . '@@' . $validation->messages . '|||';

        }

        return "¬¬¬".$validationsString;
    }

    protected function processJSONValidations($file,$key)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        if (!property_exists($fields, 'validations')) {
            return '';
        }

        $validationsString = '';
        // echo $fields->validations->store;

        $arr = [];

        switch ($key) {
            case 'store':
                $arr = $fields->validations->store;
                break;
            case 'update':
                $arr = $fields->validations->update;
                break;
            case 'delete':
                $arr = $fields->validations->delete;
                break;
        }

        foreach ($arr as $validation) {


            $validationsString .= $validation->field . '¬¬' . $validation->rules . '@@' . $validation->messages . '|||';

        }

        $validationsString = rtrim($validationsString, '|||');

        return $validationsString;
    }
}
