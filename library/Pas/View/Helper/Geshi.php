<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * Geshi helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_Geshi extends Zend_View_Helper_Abstract{
	
/**
 * Path the configuration file can be found on.
 *
 * @var string
 */
	public $configPath;

/**
 * The Container Elements that could contain highlightable code
 *
 * @var array
 */
	public $validContainers = array('pre');

/**
 * Replace containers with divs to increase validation
 *
 * @var string
 */
	public $containerMap = array('pre' => array('div class="code"', 'div'));

/**
 * The languages you want to highlight.
 *
 * @var array
 */
	public $validLanguages = array(
		'css', 'html', 'php', 'javascript', 'python', 'sql',
		'ruby', 'coffeescript', 'bash',
	);

/**
 * Default language to use if no valid language is found.  leave null to require a language attribute
 * to be set on each container.
 *
 * @var mixed  false for no default language, String for the default language
 */
	public $defaultLanguage = false;

/**
 * The Attribute use for finding the code Language. 
 *
 * Common choices are lang and class
 *
 * @var string
 */
	public $langAttribute = 'lang';

/**
 * GeSHi Instance
 *
 * @var object
 */
	protected $_geshi = null;

/**
 * Show the Button that can be used with JS to switch to plain text.
 *
 * @var bool
 */
	public $showPlainTextButton = true;

/**
 * Highlight a block of HTML containing defined blocks.  Converts blocks from plain text
 * into highlighted code.
 *
 * @param string $htmlString
 * @return void
 */
	public function highlight($htmlString) {
		$tags = implode('|', $this->validContainers);
		//yummy regex
		$pattern = '#(<('. $tags .')[^>]'.$this->langAttribute.'=["\']+([^\'".]*)["\']+>)(.*?)(</\2\s*>|$)#s';
		/*
			matches[0] = whole string
			matches[1] = open tag including lang attribute
			matches[2] = tag name
			matches[3] = value of lang attribute
			matches[4] = text to be highlighted
			matches[5] = end tag
		*/
		$html = preg_replace_callback($pattern, array($this, '_processCodeBlock'), $htmlString);
		return $this->output( $html );
	}

/**
 * Highlight all the provided text as a given language.
 *
 * @param string $text The text to highight.
 * @param string $language The language to highlight as.
 * @return string Highlighted HTML.
 */
	public function geshi($text, $language) {
		$this->_getGeshi();
		$this->_geshi->set_source($text);
		$this->_geshi->set_language($language);
		return $this->_geshi->parse_code();
	}

/**
 * Highlight all the provided text as a given language.
 * Formats the results into an HTML table.  This makes handling wide blocks
 * of code in a narrow page/space possible.
 *
 * @param string $text The text to highight.
 * @param string $language The language to highlight as.
 * @return string Highlighted HTML.
 */
	public function highlightAsTable($text, $language) {
		$this->_getGeshi();
		$this->_geshi->set_source($text);
		$this->_geshi->set_language($language);
		$this->_geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
		$highlight = $this->_geshi->parse_code();
		return $this->_convertToTable($highlight);
	}

	protected function _convertToTable($highlight) {
		preg_match_all(
			'#<li\s*class\="li\d">(.*)</li>#',
			$highlight,
			$lines,
			PREG_SET_ORDER
		);
		$numbers = $code = array();
		foreach ($lines as $i => $line) {
			$numbers[] = sprintf('<div class="de1">%d</div>', $i + 1);
			$code[] = $line[1];
		}
		$template = <<<HTML
<table class="code" cellspacing="0" cellpadding="0">
<tbody>
	<tr><td class="code-numbers">%s</td>
	<td class="code-block">%s</td></tr>
</tbody>
</table>
HTML;
		return sprintf(
			$template,
			implode("\n", $numbers),
			implode("\n", $code)
		);
	}

/**
 * Get the instance of GeSHI used by the helper.
 */
	protected function _getGeshi() {
		if (!$this->_geshi) {
			$this->_geshi = new geshi();
			$this->_configureInstance($this->_geshi);
		}
		return $this->_geshi;
	}

/**
 * Preg Replace Callback
 * Uses matches made earlier runs geshi returns processed code blocks.
 *
 * @return string Completed replacement string
 */
	protected function _processCodeBlock($matches) {
		list($block, $openTag, $tagName, $lang, $code, $closeTag) = $matches;
		unset($matches);

		// check language
		$lang = $this->validLang($lang);
		$code = html_entity_decode($code, ENT_QUOTES); // decode text in code block as GeSHi will re-encode it.

		if (isset($this->containerMap[$tagName])) {
			$patt = '/' . preg_quote($tagName) . '/';
			$openTag = preg_replace($patt, $this->containerMap[$tagName][0], $openTag);
			$closeTag = preg_replace($patt, $this->containerMap[$tagName][1], $closeTag);
		}

		if ($this->showPlainTextButton) {
			$button = '<a href="#null" class="geshi-plain-text">Show Plain Text</a>';
			$openTag = $button . $openTag;
		}

		if ($lang) {
			$highlighted = $this->highlightText(trim($code), $lang);
			return $openTag . $highlighted . $closeTag;
		}
		return $openTag . $code . $closeTag;
	}

/**
 * Check if the current language is a valid language.
 *
 * @param string $lang Language
 * @return mixed.
 */
	public function validLang($lang)  {
		if (in_array($lang, $this->validLanguages)) {
			return $lang;
		}
		if ($this->defaultLanguage) {
			return $this->defaultLanguage;
		}
		return false;
	}

/**
 * Configure a geshi Instance the way we want it. 
 * app/config/geshi.php
 *
 * @param Geshi $geshi
 * @return void
 */
	protected function _configureInstance($geshi) {
		$geshi->set_header_type(GESHI_HEADER_NONE);
	$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
	$geshi->enable_classes();
	$geshi->set_tab_width(4);
	}
} 