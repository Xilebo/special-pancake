<?php 
class Package {

	/**
	 * @param $type "module" or "plugin"
	 * @param $name the name of the package
	 * @param $category "classes" or "functions"
	 */
	private static function loadNonExecutable($type, $name, $category) {
		global $config;
		if (isset($config[$type][$name][$category]) {
			foreach ($config[$type][$name][$category] as $file) {
				include_once $config[$type][$name]['path'] . $file;
			}
		}
	}

	/**
	 * @param $type "module" or "plugin"
	 * @param $name the name of the package
	 */
	private static function loadClassesAndFunctions($type, $name) {
		loadNonExecutable($type, $name, 'classes');
		loadNonExecutable($type, $name, 'functions');
	}

	private static function loadExecutables ($type, $name) {
		global $config;
		if (isset($config[$type][$name]['execute']) {
			ksort($config[$type][$name]['execute']);
			foreach ($config[$type][$name]['execute'] as $file) {
				registerExecutable($config[$type][$name]['path'] . $file);
			}
		}
	}

	static function load ($type, $name) {
		global $config;
		$path = $config[$type][$name];
		if (substr($packagePath, -1) !== "/") {
			$packagePath .= '/';
		}

		$config[$type][$name] = parse_ini_file($path . 'config.ini', true);
		$config[$type][$name]['path'] = $path;

		Package::loadClasses($type, $name);
		Package::loadExecutables($type, $name);
	}

	static function loadAll () {
		global $config;
		foreach ($config['module'] as $name) {
			Package::load('module', $name);
		}
		foreach ($config['plugin'] as $name) {
			Package::load('plugin', $name);
		}
	}

}
?>
