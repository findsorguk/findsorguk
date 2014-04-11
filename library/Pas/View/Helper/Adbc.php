<?php 
/**
 * A view helper for turning dates into AD or BC calenderical dates 
 * @category   	Pas
 * @package    	Pas_View_Helper
 * @subpackage 	Abstract
 * @copyright  	Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    	GNU
 * @see 		Zend_View_Helper_Abstract
 * @author     	Originally TW Bell, extended for Zend by Daniel Pett
 * @version		1
 * @since		26 September 2011
 * @uses Zend_Validate_Int
 */
class Pas_View_Helper_Adbc extends Zend_View_Helper_Abstract {
	
	/** A function for turning the integer into a string with AD or BC added
	 * 
	 * @param $date integer 
	 * @param $suffix string
	 * @param $prefix string
	 */
	
	public function adbc($date = NULL, $suffix="BC", $prefix="AD") {
	$validator = new Zend_Validate_Int();
	if($validator->isValid($date)){
	if ($date  < 0) {    
   	 return  abs($date) . ' ' . $suffix;
    } else if ($date > 0) {
		return $prefix . ' ' . abs($date);
	} else if ($date === 0) {
		return false;
	}
	} else {
		return false;
	}
	}
 }
