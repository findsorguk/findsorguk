<?php
/**
 *
 * @author dpett
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * SplitName helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_SplitName extends Zend_View_Helper_Abstract {
	
	/**
	 * 
	 */
	public function splitName($name) {
	list($first, $last) = explode(' ', $name);
	return $first;
	}
	
}

