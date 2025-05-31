<?php

namespace App\Modules;

class Modular
{
	private static $modules = null;

	public static function getModules()
	{
		if (!self::$modules) {
			self::$modules = [];
			$entryModules = explode(',', env('ENTRY_MODULES', ''));
			foreach ($entryModules as $moduleName) {
				self::loadModules($moduleName);
			}
		}
		return self::$modules;
	}

	private static function loadModules($moduleName)
	{
		if (!$moduleName) return;

		if (in_array($moduleName, array_column(self::$modules, 'name'))) return;

		$moduleFile = self::getPath($moduleName, '/module.php');
		if (file_exists($moduleFile)) {
			$module = require_once $moduleFile;
			$module['name'] = $moduleName;
			$module['config'] = $module['config'] ?? [];
			$module['requires'] = $module['requires'] ?? [];
			$module['includes'] = $module['includes'] ?? [];
		} else {
			$module = [
				'name' => $moduleName,
				'config' => [],
				'requires' => [],
				'includes' => [],
			];
		}

		foreach ($module['requires'] as $name) {
			self::loadModules($name);
		}

		self::$modules[] = $module;

		foreach ($module['includes'] as $name) {
			self::loadModules($name);
		}
	}

	public static function getClass($moduleName, $className = 'Module')
	{
		return 'App\\Modules\\' . $moduleName . '\\' . $moduleName . $className;
	}

	public static function getPath($moduleName, $subPath = '')
	{
		return base_path("app/Modules/$moduleName") . $subPath;
	}

	public static function mergeConfig($configKey, $baseConfig)
	{
		$modules = self::getModules();
		$config = $baseConfig;
		foreach ($modules as $module) {
			$moduleConfig = $module["config.$configKey"] ?? [];
			$config = self::_deepMerge($config, $moduleConfig);
		}
		return $config;
	}

	private static function _deepMerge($config, $moduleConfig)
	{
		$merged = $config;

		foreach ($moduleConfig as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				if (array_keys($value) === range(0, count($value) - 1)) {
					// Numeric array, concat values
					$merged[$key] = array_merge($merged[$key], $value);
				} else {
					// Assoc array, override or set value
					$merged[$key] = self::_deepMerge($merged[$key], $value);
				}
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}

	public static function requireAll($filePath)
	{
		$modules = self::getModules();
		foreach ($modules as $module) {
			$file = self::getPath($module['name'], $filePath);
			if (file_exists($file)) {
				require_once $file;
			}
		}
	}
}
