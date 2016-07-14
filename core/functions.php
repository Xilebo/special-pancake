<?php
/**
 * Returns all files (and directories) in a given path. The entities '.' and 
 * '..' are stripped.
 * The parameter $type is optional. It can be used to get only files of a 
 * certain type.
 */
function getFiles($path, $type)
{
	$files = array();
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle)))
		{
			if (($file != ".") && ($file != "..")){
				$tmp = explode(".", $file);
				$fileType = array_pop($tmp);
				if ((!isset($type)) || ($fileType == $type)) {
					$files[] = $file;
				}
			}
		}
		closedir($handle);
		asort($files);
		return $files;
	}
}

function addTabStops($text, $tabCount) {
	$tab = '	';

	for ($i = 0; $i < $tabCount; $i++) {
		$text .= $tab;
	}
	return $text;
}

/**
 * This function escapes all non-word characters in a given string for use in
 * regular expressions.
 */
function regexEscape($string) {
	$pattern = '/(\W)/';
	$replacement = '\\\\${1}';
	return preg_replace($pattern, $replacement, $string);
}

function httpVarGet($param) {
	$result = '';
	if (isset($_POST[$param])) {
		$result = $_POST[$param];
	} elseif (isset($_GET[$param])) {
		$result = $_GET[$param];
	}
	return $result;
}

function httpVarIsSet($param) {
	$result = isset($_POST[$param]) || isset($_GET[$param]);
	return $result;
}

function registerExecutable($file) {
	global $executables;
	$executables[] = $file;
}

function executeExecutables() {
	global $executables;
	foreach ($executables as $executable) {
		include_once $executable;
	}
}

?>
