<?php 
class Package {

	private static function loadClasses($type, $name) {
		global $config;
		if (isset($config[$type][$name]['classes']) {
			foreach ($config[$type][$name]['classes'] as $file) {
				include_once $config[$type][$name]['path'] . $file;
			}
		}
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
}
?>
