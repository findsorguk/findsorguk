<?php
/**
 * A view helper for converting html characters for XML display
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Xmlconvert extends Zend_View_Helper_Abstract {
	
	/** Convert characters for display in XML
	 * 
	 * @param string $str
	 */
	public function xmlconvert($str) {
	$temp = '__TEMP_AMPERSANDS__';
	// Replace entities to temporary markers so that 
	// ampersands won't get messed up
	$str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);
	$str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
	
	$str = str_replace(
	array("&", "<",">", "\"", "'", "-", "£", "&nbsp;", "&ndash;"),
	array("&amp;", "&lt;", "&gt;", "&quot;", "&#39;", 
	"&#45;","&#163;","&#160","&#39"),$str);
	// Decode the temp markers back to entities		
	$str = preg_replace("/$temp(\d+);/","&#\\1;",$str);
	$str = preg_replace("/$temp(\w+);/","&\\1;", $str);
	return $str;
	}
}

