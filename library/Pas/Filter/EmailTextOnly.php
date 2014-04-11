<?php

/**
* Filter extension based on HTML purifier for allowing Basic HTML on forms and displays
*
*
* @category   Pas
* @package    Filter
* @subpackage Interface
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Filter_EmailTextOnly implements Zend_Filter_Interface {
   
	protected $_htmlPurifier;
	/** Set up the filter's options
	* @return object
	*/   
	public function __construct($options = null) {
	$config = HTMLPurifier_Config::createDefault();
	$this->_htmlPurifier = new HTMLPurifier($config);
	$config->set('Cache.SerializerPath',  CACHE_PATH . '/htmlpurifier');
	$config->set('HTML.Allowed', 'a[href]');
	$config->set('AutoFormat.RemoveEmpty.RemoveNbsp',TRUE);
	$config->set('AutoFormat.RemoveEmpty', TRUE);
	$config->set('AutoFormat.Linkify', true);
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