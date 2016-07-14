<?php 

if ($moduleConfig['loadNext']['type'] == 'module') {
	$tmpName = $moduleConfig['loadNext']['name']
	$tmpPath = $config['modules'][$moduleName];
	$moduleConfig[$tmpName] = parse_ini_file($tmpPath . 'config.ini', true);
	
	unset($tmpName, $tmpPath);
} elseif ($moduleConfig['loadNext']['type'] == 'plugin') {
	// TODO
}






$packageConfig = parse_ini_file($path . 'config.ini', true);
if (isset($packageConfig['classes']) {
	foreach ($packageConfig['classes'] as $file) {
		include_once $path . $file;
	}
}
ksort($moduleConfig['pageHandler']['execute']);

?>
