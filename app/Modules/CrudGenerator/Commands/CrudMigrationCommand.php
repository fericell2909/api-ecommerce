<?php

namespace App\Modules\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudMigrationCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crudapimigration
                            {name : The name of the migration.}
                            {--migration-namespace= : the namespacemigration.}
                            {--schema= : The name of the schema.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : Foreign keys.}
                            {--pk=id : The name of the primary key.}
                            {--soft-deletes=no : Include soft deletes fields.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     *  Migration column types collection.
     *
     * @var array
     */
    protected $typeLookup = [
        'string' => 'string',
        'char' => 'char',
        'date' => 'date',
        'datetime' => 'dateTime',
        'time' => 'time',
        'timestamp' => 'timestamp',
        'text' => 'text',
        'mediumtext' => 'mediumText',
        'longtext' => 'longText',
        'json' => 'json',
        'jsonb' => 'jsonb',
        'binary' => 'binary',
        'number' => 'integer',
        'integer' => 'integer',
        'bigint' => 'bigInteger',
        'unsignedBigInteger' =>  'unsignedBigInteger',
        'mediumint' => 'mediumInteger',
        'tinyint' => 'tinyInteger',
        'smallint' => 'smallInteger',
        'boolean' => 'boolean',
        'decimal' => 'decimal',
        'double' => 'double',
        'float' => 'float',
        'enum' => 'enum',
        'unique' => 'unique',
        'nullable' => 'nullable',
        'comment' => 'comment',
        'default' => 'default',
    ];

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/../stubs/migration.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);
        $datePrefix = date('Y_m_d_His');
        $namespace = $this->option('migration-namespace');
        //database_path('/migrations/')
        echo $namespace;
        return $namespace   . $datePrefix . '_create_' . $name . '_table.php';
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

        $tableName = $this->argument('name');
        $className = 'Create' . str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName))) . 'Table';

        $fieldsToIndex = trim($this->option('indexes')) != '' ? explode(',', $this->option('indexes')) : [];
        $foreignKeys = trim($this->option('foreign-keys')) != '' ? explode(',', $this->option('foreign-keys')) : [];

        $schema = rtrim($this->option('schema'), ';');
        $fields = explode(';', $schema);
        //var_dump($fields);
        //echo $fields;
        $schemaFields = '';
        $tabIndent = '    ';
        $count = 0;

        if($schema){
            foreach($fields as $field){
                $fieldArray = explode('#', $field);

                $keyfieldname = explode('@', $fieldArray[0]);

                $keyfieldattributename = explode('||', $keyfieldname[1]);

                if(count($keyfieldattributename) > 1){

                    $schemaFields .= "\$table->" . $keyfieldattributename[0] . "('" . $keyfieldname[0] . "', " . $keyfieldattributename[1] . ")";

                } else {

                    if($keyfieldname[1] != null && $keyfieldname[1] != '') {
                        $schemaFields .= "\$table->" . $keyfieldname[1] . "('" . $keyfieldname[0] . "')";
                    }
                }

                $moretyes = explode('@', $fieldArray[1]);
                // print_r($moretyes);
                // echo count($moretyes);
                foreach($moretyes as $types) {

                    $exttype = explode('||', $types);
                //     print_r($exttype);
                // echo count($exttype);
                // echo "\"";
                // echo "type por => " .$exttype[0];
                    if(count($exttype) > 1){

                        if($exttype[0] == 'comment') {
                            $schemaFields .= "->" . $exttype[0] . "('" . $exttype[1] . "')";
                        } else {
                            $schemaFields .= "->" . $exttype[0] . "(" . $exttype[1] . ")";
                        }

                    } else {
                        if($exttype[0] != null && $exttype[0] != '') {
                            $schemaFields .= "->" . $exttype[0] . "()";
                        }
                        // if(count($moretyes) == 1){
                        //     $schemaFields .= "->" . $exttype[0] . "()";
                        // }
                    }
                }
                $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
            }
        }

        // echo $schemaFields;
        // $data = array();

        // if ($schema) {
        //     $x = 0;
        //     foreach ($fields as $field) {

        //         $fieldArray = explode('#', $field);
        //         // $fieldArray = explode('#', $field);

        //         //$data[$x]['name'] = trim($fieldArray[0]);
        //         // $data[$x]['type'] = trim($fieldArray[1]);
        //         // if (($data[$x]['type'] === 'select'
        //         //         || $data[$x]['type'] === 'enum')
        //         //     && isset($fieldArray[2])
        //         // ) {
        //         //     $options = trim($fieldArray[2]);
        //         //     $data[$x]['options'] = str_replace('options=', '', $options);
        //         // }

        //         // $data[$x]['modifier'] = '';

        //         // $modifierLookup = [
        //         //     'comment',
        //         //     'default',
        //         //     'first',
        //         //     'nullable',
        //         //     'unsigned',
        //         //     'unique'
        //         // ];

        //         // if (isset($fieldArray[2]) && in_array(trim($fieldArray[2]), $modifierLookup)) {

        //         //     $data[$x]['modifier'] = "->" . trim($fieldArray[2]) . "()";
        //         // }

        //         // $x++;
        //     }
        // }

        // $tabIndent = '    ';

        // $schemaFields = '';
        // foreach ($data as $item) {
        //     if (isset($this->typeLookup[$item['type']])) {
        //         $type = $this->typeLookup[$item['type']];

        //         if ($type === 'select' || $type === 'enum') {
        //             $enumOptions = array_keys(json_decode($item['options'], true));
        //             $enumOptionsStr = implode(",", array_map(function ($string) {
        //                 return '"' . $string . '"';
        //             }, $enumOptions));
        //             $schemaFields .= "\$table->" . $type . "('" . $item['name'] . "', [" . $enumOptionsStr . "])";
        //         } else {
        //             $schemaFields .= "\$table->" . $type . "('" . $item['name'] . "')";
        //         }
        //     } else {
        //         $schemaFields .= "\$table->string('" . $item['name'] . "')";
        //     }

        //     // Append column modifier
        //     $schemaFields .= $item['modifier'];
        //     $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        // }

        // // add indexes and unique indexes as necessary
        // foreach ($fieldsToIndex as $fldData) {
        //     $line = trim($fldData);

        //     // is a unique index specified after the #?
        //     // if no hash present, we append one to make life easier
        //     if (strpos($line, '#') === false) {
        //         $line .= '#';
        //     }

        //     // parts[0] = field name (or names if pipe separated)
        //     // parts[1] = unique specified
        //     $parts = explode('#', $line);
        //     if (strpos($parts[0], '|') !== 0) {
        //         $fieldNames = "['" . implode("', '", explode('|', $parts[0])) . "']"; // wrap single quotes around each element
        //     } else {
        //         $fieldNames = trim($parts[0]);
        //     }

        //     if (count($parts) > 1 && $parts[1] == 'unique') {
        //         $schemaFields .= "\$table->unique(" . trim($fieldNames) . ")";
        //     } else {
        //         $schemaFields .= "\$table->index(" . trim($fieldNames) . ")";
        //     }

        //     $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        // }

        // foreign keys
        foreach ($foreignKeys as $fk) {
            $line = trim($fk);

            $parts = explode('#', $line);

            // if we don't have three parts, then the foreign key isn't defined properly
            // --foreign-keys="foreign_entity_id#id#foreign_entity#onDelete#onUpdate"
            if (count($parts) == 3) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                    . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')";
            } elseif (count($parts) == 4) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                    . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')"
                    . "->onDelete('" . trim($parts[3]) . "')" . "->onUpdate('" . trim($parts[3]) . "')";
            } elseif (count($parts) == 5) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                    . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')"
                    . "->onDelete('" . trim($parts[3]) . "')" . "->onUpdate('" . trim($parts[4]) . "')";
            } else {
                continue;
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        $primaryKey = $this->option('pk');
        $softDeletes = $this->option('soft-deletes');

        $softDeletesSnippets = '';
        if ($softDeletes == 'yes') {
            $softDeletesSnippets = "\$table->softDeletes();\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        $schemaUp =
            "Schema::create('" . $tableName . "', function (Blueprint \$table) {\n" .
            $tabIndent . $tabIndent . $tabIndent . "\$table->bigIncrements('" . $primaryKey . "');\n" .
            $tabIndent . $tabIndent . $tabIndent . $softDeletesSnippets .
            $schemaFields .
             "\$table->timestamps();\n" .
            "});";

        $schemaDown = "Schema::drop('" . $tableName . "');";

        return $this->replaceNamespace($stub, $name)->replaceSchemaUp($stub, $schemaUp)
            ->replaceSchemaDown($stub, $schemaDown)
            ->replaceClass($stub, $className);
    }

    /**
     * Replace the schema_up for the given stub.
     *
     * @param  string  $stub
     * @param  string  $schemaUp
     *
     * @return $this
     */
    protected function replaceSchemaUp(&$stub, $schemaUp)
    {
        $stub = str_replace('{{schema_up}}', $schemaUp, $stub);

        return $this;
    }

    /**
     * Replace the schema_down for the given stub.
     *
     * @param  string  $stub
     * @param  string  $schemaDown
     *
     * @return $this
     */
    protected function replaceSchemaDown(&$stub, $schemaDown)
    {
        $stub = str_replace('{{schema_down}}', $schemaDown, $stub);

        return $this;
    }
}
