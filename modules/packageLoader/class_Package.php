<?php
class Package {

	/**
	 * @param $type "module" or "plugin"
	 * @param $key the key the package is registered under
	 * @param $category "classes" or "functions"
	 */
	private static function loadNonExecutable($type, $key, $category) {
		global $config;
		if (!isset($config[$type][$key][$category])) return;
		foreach ($config[$type][$key][$category] as $file) {
			include_once $config[$type][$key]['path'] . $file;
		}
	}

	/**
	 * @param $type "module" or "plugin"
	 * @param $key the key the package is registered under
	 */
	private static function loadClassesAndFunctions($type, $key) {
		Package::loadNonExecutable($type, $key, 'classes');
		Package::loadNonExecutable($type, $key, 'functions');
	}

	private static function loadExecutables ($type, $key) {
		global $config;
		if (!isset($config[$type][$key])) return;
		if (isset($config[$type][$key]['execute'])) {
			ksort($config[$type][$key]['execute']);
			foreach ($config[$type][$key]['execute'] as $file) {
				registerExecutable($config[$type][$key]['path'] . $file);
			}
		}
	}

	static function load ($type, $key) {
		global $config;
		if (!isset($config[$type][$key])) return;
		if (isset ($config[$type][$key]['isLoaded']) &&
				($config[$type][$key]['isLoaded'] === true)) return;

		$path = $config[$type][$key];
		if (substr($packagePath, -1) !== "/") {
			$packagePath .= '/';
		}

		$config[$type][$key] = parse_ini_file($path . 'config.ini', true);
		loadLocalConfig(); 
		$config[$type][$key]['path'] = $path;

		Package::loadClassesAndFunctions($type, $key);
		Package::loadExecutables($type, $key);
		$config[$type][$key]['isLoaded'] = true;
	}

	static function loadAll () {
		global $config;
		if (isset($config['module'])) {
			foreach ($config['module'] as $key => $tmp) {
				Package::load('module', $key);
			}
		}
		if (isset($config['plugin'])) {
			foreach ($config['plugin'] as $key => $tmp) {
				Package::load('plugin', $key);
			}
		}
	}

}
?>
