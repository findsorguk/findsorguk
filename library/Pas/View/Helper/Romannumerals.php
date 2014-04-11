<?php
/**
 * A view helper for turning dates into Roman numerals
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Zend_View_Helper_RomanNumerals extends Zend_View_Helper_Abstract {
	/**
	 * The function below creates a roman numeral from a number
	 * @param int $num
	 * @return string
	 * @uses Zend_Validate_Int
	 */
	public function romanNumerals($date) {
	//Check if the number is an integer
	$validator = new Zend_Validate_Int();
	if($validator->isValid($date)){
	$n = intval($date);
    $res = '';
    /** Create the array of Roman numerals based on numbers
	*/
    $roman_numerals = array(
	'M'  => 1000, 'CM' => 900, 'D'  => 500,
	'CD' => 400, 'C'  => 100, 'XC' => 90,
	'L'  => 50, 'XL' => 40, 'X'  => 10,
	'IX' => 9, 'V'  => 5, 'IV' => 4,
  	'I'  => 1);
    foreach ($roman_numerals as $roman => $number)   {
	/**
	* Divide number to get matches 
	*/
	$matches = intval($n / $number);
	/**
	*  assign the roman char * $matches 
	*/
	$res .= str_repeat($roman, $matches);
	/** 
	 * subtract from the number 
	 */
	$n = $n % $number;
    }
    /** return the resulting string as a roman numeral
    */ 
    return $res;
    }
	}

}