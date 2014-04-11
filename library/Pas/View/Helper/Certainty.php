<?php
/**
 * A basic view helper for displaying certainty for object types 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Certainty extends Zend_View_Helper_Abstract {
	
	/**
 	* Check for the certainty
 	* @param $int The certainty lookup number 
 	*/
	public function certainty($int){
	$validator = new Zend_Validate_Int();
	if($validator->isValid($int)){
	switch ($int) {
		case 1:
			$cert = 'Certain';
		break;
		case 2:
			$cert = 'Probably';
		break;
		case 3:
			$cert = 'Possibly';
		break; 
		default:
			return false;
		break;
		}
	return $cert;
	} else {
		return false;
	}
	}
}