<?php 
/**
 * A view helper for displaying name or the Latin phrase
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_Namethem extends Zend_View_Helper_Abstract {

	/** Name the person based on string
	 * @access public
	 * @param string $string
	 * @return string $name
	 */
	public function namethem($string) {
	if(is_null($string)) {
	$name = '<em>Nemo hic adest illius nominis</em>';
	} else {
	$name = $string;
	}
	return $name;
	}
}
