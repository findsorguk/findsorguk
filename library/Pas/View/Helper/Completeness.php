<?php
/**
 * A view helper for rendering completeness of object
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Validate_Int
 */
class Pas_View_Helper_Completeness extends Zend_View_Helper_Abstract {
	/** Determine the completeness from lookup value
	 * 
	 * @param integer $string
	 * @return string
	 */
	public function completeness($string) {
	$validator = new Zend_Validate_Int();
	if($validator->isValid($string)){
	switch ($string) {
		case 1:
			$comp = 'Fragment';
			break;
		case 2:
			$comp = 'Incomplete';
			break;
		case 3:
			$comp = 'Uncertain';
			break; 
		case 4:
			$comp = 'Complete';
			break;
		default:
			return false;
			break;
	}
	return $comp;
	}
	}
}