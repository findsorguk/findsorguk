<?php

/**
* Filter extension based on HTML purifioer for allowing Basic HTML on forms and displays
*
*
* @category   Pas
* @package    Filter
* @subpackage Interface
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Filter_Purifier implements Zend_Filter_Interface {
   
	protected $_htmlPurifier;
	/** Set up the filter's options
	* @return object
	*/
    public function __construct($options = null){
	$config = HTMLPurifier_Config::createDefault();
	$this->_htmlPurifier = new HTMLPurifier($config);
	$config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
	//$config->set('HTML.Doctype', 'HTML 4.01 Strict');
	$config->set('HTML.Allowed', '');
	$config->set('AutoFormat.RemoveEmpty.RemoveNbsp',TRUE);
	$config->set('AutoFormat.RemoveEmpty', TRUE);
	$config->set('AutoFormat.Linkify', false);
	$config->set('AutoFormat.AutoParagraph', false);
	$config->set('HTML.TidyLevel', 'heavy');
	}

	/** Filter the input
	* @param string $value
	* @return object
	*/
    public function filter($value)  {
        return $this->_htmlPurifier->purify($value);
    }

}