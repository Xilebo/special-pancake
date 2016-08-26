<?php
class Log {
	private static $log = NULL;

	private $errors = array();
	private $warnings = array();
	private $messages = array();

	static function getLog() {
		if (self::$log == NULL) {
			self::$log = new Log();
		}
		return self::$log;
	}

	private function __construct(){
	}

	function logError($errorMessage, $module = "") {
		$entry = array('module' => $module, 'text' => $errorMessage);
		$this->errors[] = $entry;
	}

	function logWarning($warning, $module = "") {
		$entry = array('module' => $module, 'text' => $warning);
		$this->warnings[] = $entry;
	}

	function logMessage($text, $module = "") {
		$entry = array('module' => $module, 'text' => $text);
		$this->messages[] = $entry;
	}

	function hasErrors() {
		$errorCount = count($this->errors);
		$result = ($errorCount > 0) ? true : false;
		return $result;
	}

	function hasWarnings() {
		$WarningCount = count($this->warnings);
		$result = ($WarningCount > 0) ? true : false;
		return $result;
	}

	function hasMessages() {
		$MessageCount = count($this->messages);
		$result = ($MessageCount > 0) ? true : false;
		return $result;
	}

	function hasEntrys() {
		if (($this->hasErrors())
			|| ($this->hasWarnings())
			|| ($this->hasMessages())) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	function getErrors() {
		return $this->errors;
	}

	function getWarnings() {
		return $this->warnings;
	}

	function getMessages() {
		return $this->messages;
	}

	function toJson() {
		$data = array();
		if ($this->hasErrors()) {
			$data['errors'] = $this->errors;
		}
		if ($this->hasWarnings()) {
			$data['warnings'] = $this->warnings;
		}
		if ($this->hasMessages()) {
			$data['messages'] = $this->messages;
		}
		$result = json_encode($data);
		return $result;
	}

	function transferToJs() {
		$js = 'log = ' . $this->toJson() . ';';
		Page::getPage()->addJs($js);
	}

}
?>
