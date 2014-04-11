<?php
/**
 * A view helper for truncating text and adding an ellipsis at the end
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Ellipsisstring extends Zend_View_Helper_Abstract {

	/** Shorten the text string to 300 characters.
	 * 
	 * @param string $string
	 * @param integer $max
	 * @param string $rep
	 */
    public function ellipsisstring($string, $max = 300, $rep = '&hellip;') {
	if (strlen($string) < $max) {
	return $string;
	} else  {
	$leave = $max - strlen ($rep);
	return strip_tags(substr_replace($string, $rep, $leave),'<br><a><em>');
	}
	}
}