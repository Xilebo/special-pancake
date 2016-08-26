<?php

$config = array();
loadGlobalConfig();
loadLocalConfig();

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
