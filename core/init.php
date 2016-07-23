<?php

// in case of conflict, localconf overwrites global config
$config = parse_ini_file('core/config.ini', true);
$config = array_merge($config,parse_ini_file('localconf.ini', true)); 

// add trailing '/'
if (isset($config['module'])) {
	foreach ($config['module'] as $key => $value) {
		if (substr($value, -1) !== "/") {
			$config['module'][$key] = $value . '/';
		}
	}
}

$executables = array();


?>
