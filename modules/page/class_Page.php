<?php
class Page {
	private static $page = NULL;
	private static $frameTemplate = 'document_frame';

	private $module = "page.php";
	private $openPosition = '<** ';
	private $closePosition = ' **>';
	private $positionHead = 'HEAD';
	private $positionBody = 'BODY';

	private $newLine = '
';

	private $content = 'ERROR: no content';

	private $positions = array();

	static function getPage() {
		if (self::$page == NULL) {
			self::$page = new Page();
		}
		return self::$page;
	}

	private function __construct() {
		global $config;

		$this->positions = array(
				$this->positionHead => 1,
				$this->positionBody => 1
		);

		$this->reloadFrame();

		// TODO add function to add external Files _before_ auto-added logic
		$this->addJsFile('http://code.jquery.com/jquery-1.12.0.min.js'); 
		//~ $this->addJsFile('http://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js');

		$this->addCssFolder($config['path']['css']);
		$this->addJsFolder($config['path']['javascript']);
	}

	private function getPositionTag($position) {
		$position = strtoupper($position);
		return $this->openPosition . $position . $this->closePosition;
	}

	private function getTemplate($templateName) {
		global $config;

		$templateContent = "";
		// TODO check if file exists
		try {
			$filename = $config['path']['templates'] . $templateName . ".html";
			$handle = fopen($filename, "r");
			$templateContent = fread($handle, filesize($filename));
		} catch (Exception $e) {
			Log::getLog()->logError($e, $this->module);
		}
		if (get_resource_type($handle) === 'file') {
			fclose($handle);
		}
		return $templateContent;
	}

	private function applyConfigData() {
		global $config;

		$this->replacePosition('LANGUAGE', $config['language']);
		$this->replacePosition('CHARSET', $config['html']['charset']);
		$this->replacePosition('TITLE', $config['html']['title']);
	}

	/**
	 * This changes the currently used frame template. Previous changes 
	 * to the page content are lost.
	 * @param $template The name of the template corresponds with the filename
	 * without the postfix. E.g. if $template is "main" the file
	 * "[templatePath]/main.html" will be used.
	 */
	function setFrame($template) {
		self::$frameTemplate = $template;
		self::getPage()->reloadFrame();
	}

	/**
	 * This reloads the template, effectively resetting the content.
	 */
	function reloadFrame() {
		$this->content = $this->getTemplate(self::$frameTemplate);
		$this->applyConfigData();
	}

	/**
	 * Adds an element with the given properties at the given position. A new
	 * position is generated in the element. The name of the new position is the
	 * same as the id.
	 * If the given id already exists, it may be altered to make it unique. The
	 * return parameter is the id/position that is actually used.
	 * @param $type what kind of element should be added - eg. 'br' for <br />
	 * or 'div' for <div></div>
	 * @param $id if an id is given, it will be used as 'id'-attribute as well
	 * as for the new positioning-tag (eg. <div id="$id"><** $id **></div>).
	 */
	public function addElement($type, $position = '', $id = '', $parameters = [],
			$selfClosing = false) {
		// TODO check if $position is registered in $this->positions
		// TODO assert uniqueness of $id
		$newElement = '<' . $type;
		if ($id != '') {
			$newElement .= ' id="' . $id . '"';
		}
		foreach ($parameters as $parameter => $value) {
			$newElement .= ' ' . $parameter . '="' . $value . '"';
		}
		if ($selfClosing) {
			$newElement .= ' />';
		} else {
			$newElement .= '>';
			if ($id != '') {
				$newElement .= $this->newLine;
				// TODO add tabs
				$newElement .= $this->getPositionTag($id);
				$newElement .= $this->newLine;
			}
			$newElement .= '</' . $type . '>';
		}
		$this->addRawHtml($newElement, $position);
		return $id;
	}

	public function addTemplate($template, $position = '') {
		$template = $this->getTemplate($template);
		// TODO check for positions
		// TODO assure uniqueness of positions
		// TODO count leading tabs and register positions
		$this->addRawHtml($template, $position);
	}

	public function addCssFolder($path) {
		$cssFiles = getFiles($path, 'css');
		foreach ($cssFiles as &$file) $this->addCssFile($path . $file);
	}

	public function addJsFolder($path) {
		$jsFiles = getFiles($path, 'js');
		foreach ($jsFiles as &$file) $this->addJsFile($path . $file);
	}

	public function replacePosition($position, $rawText) {
		$tag = $this->getPositionTag($position);
		$this->content = str_replace($tag, $rawText, $this->content);
		// TODO unregister position
	}

	public function removePosition($position) {
		$tag = $this->getPositionTag($position);
		$replace = '/[\\n\\s]*' . regexEscape($tag) . '/i'; // i = not case sensitive
		$this->content = preg_replace($replace, '', $this->content);
		// TODO unregister position
	}

	private function removeAllPositions() {
		/*
		 * This function is called _after_ the log was integrated into the
		 * page. Logged messages from this function may not be displayed.
		 */
		$open = regexEscape($this->openPosition);
		$close = regexEscape($this->closePosition);
		// (?U) = ungreedy from this point on
		$regex = '/[\\n\\s]*' . $open . '.*(?U)' . $close . '/';
		$this->content = preg_replace($regex, '', $this->content);
	}

	public function addToHead($rawText) {
		$this->addRawHtml($rawText, $this->positionHead);
	}

	public function addCssFile($path) {
		$cssTag = '<link rel="stylesheet" href="' . $path . '" />';
		$this->addToHead($cssTag);
	}

	public function addJsFile($path) {
		$cssTag = '<script type="text/javascript" src="' . $path . '"></script>';
		$this->addToHead($cssTag);
	}

	public function addJs($js) {
		$cssTag = '<script type="text/javascript">' . $js . '</script>';
		$this->addToHead($cssTag);
	}

	public function addRawHtml($rawText, $position = '') {
		if ($position == '') $position = $this->positionBody;
		$position = $this->getPositionTag($position);

		$rawText = $rawText . $this->newLine;
		//TODO if $rawText has multiple lines, each line has to get leading tabs
		$rawText = addTabStops($rawText, 1); //TODO correct tabcount
		$rawText = $rawText . $position;
		$this->content = str_ireplace($position, $rawText, $this->content);
	}

	function send() {
		Log::getLog()->transferToJs();
		$this->removeAllPositions();
		echo $this->content;
	}

}
?>
