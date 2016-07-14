<?php











// TODO needed?
$moduleConfig = array();

$moduleConfig['loadNext'] = array();
$moduleConfig['loadNext']['type'] = 'module';
$moduleConfig['loadNext']['name'] = 'pageHandler';

$moduleConfig['pageHandler'] = parse_ini_file($config['modules']['pageHandler'], true);
foreach ($moduleConfig['pageHandler']['classes'] as $file)
{
	include_once $config['modules']['pageHandler'] . $file;
}
ksort($moduleConfig['pageHandler']['execute']);

$moduleConfig['pageHandler'] = parse_ini_file($config['modules']['pageHandler'], true);
foreach ($moduleConfig['pageHandler']['classes'] as $file)
{
	include_once $config['modules']['pageHandler'] . $file;
}
ksort($moduleConfig['pageHandler']['execute']);

?>
