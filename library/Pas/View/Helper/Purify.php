<?php
/**
 * A view helper for purifying Html
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_Filter_HtmlCleaned
 */
class Pas_View_Helper_Purify extends Zend_View_Helper_Abstract {
	
	/** 
	 * Clean string for valid html
	 * @param string $value
	 */
	public function purify($value)  {
	$filter = new Pas_Filter_HtmlCleaned();
	return $filter->filter($value);
	}
}

