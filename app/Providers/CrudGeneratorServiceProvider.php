<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands(
            'App\Modules\CrudGenerator\Commands\CrudApiCommand');

        $this->commands(
                'App\Modules\CrudGenerator\Commands\CrudApiControllerCommand');

        $this->commands(
                'App\Modules\CrudGenerator\Commands\CrudRepositoryCommand');

        $this->commands('App\Modules\CrudGenerator\Commands\CrudMigrationCommand');

        $this->commands('App\Modules\CrudGenerator\Commands\CrudModelCommand');

        $this->commands('App\Modules\CrudGenerator\Commands\CrudStoreRequestCommand');

        $this->commands('App\Modules\CrudGenerator\Commands\CrudModuleCommand');

        $this->commands('App\Modules\CrudGenerator\Commands\CrudRoutesCommand');


    }
}
