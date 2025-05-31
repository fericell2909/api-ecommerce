<?php

namespace App\Modules;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;


class ModularServiceProvider extends RouteServiceProvider
{

	public function register()
	{
		$this->app->extend('config', function ($config, $app) {
			return new ModularConfigRepository($config->all());
		});

		$modules = Modular::getModules();
		foreach ($modules as $module) {
			$routesFile = Modular::getPath($module['name'], '/routes.php');
			if (file_exists($routesFile)) {
				$this->loadRoutesFrom($routesFile);
			}

			$viewDir = Modular::getPath($module['name'], '/views');
			if (file_exists($viewDir)) {
				$this->loadViewsFrom($viewDir, $module['name']);
			}

			$migrationsDir = Modular::getPath($module['name'], '/migrations');
			if (file_exists($migrationsDir)) {
				$this->loadMigrationsFrom($migrationsDir);
			}

			$providerFile = Modular::getPath($module['name'], "/" . $module['name'] . "ServiceProvider.php");
			if (file_exists($providerFile)) {
				$this->app->register(Modular::getClass($module['name'], 'ServiceProvider'));
			}
		}
	}
}

class ModularConfigRepository extends \Illuminate\Config\Repository
{
	public function __construct($items = [])
	{
		foreach ($items as $key => $value) {
			$items[$key] = Modular::mergeConfig($key, $value);
		}
		parent::__construct($items);
	}
}
