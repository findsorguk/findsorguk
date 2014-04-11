<?php 
/**
 * A validation class for checking for valid British National Grid values
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_ValidObjectType extends Zend_Validate_Abstract
{
	const NOT_VALID = 'notValid';

	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
	protected $_messageTemplates = array(
	self::NOT_VALID => 'You can only use terms in the database. 
	These appear in the autocomplete in block capitals.');

	/*
	* A function to flatten an array
	*/
	public function flatten($ar) {
    $toflat = array($ar);
    $res = array();
    while (($r = array_shift($toflat)) !== NULL) {
        foreach ($r as $v) {
			if (is_array($v)) {
				$toflat[] = $v;
			} else {
				$res[] = $v;
			}
        }
    }
    return $res;
	}
	
	/*
	* get a list of object types and then flatten the array
	*/	
	public function getTypes(){
	$objects = new ObjectTerms();
	$o =  $objects->getObjectNames();
	$terms = $this->flatten($o);
	return $terms;
	}

	/*
	* Check if value is in the array, needle in the haystack style.
	*/
	public function in_arrayi( $needle, $haystack ) { 
    $found = false; 
    foreach( $haystack as $value ) { 
    	if( strtolower( $value ) === strtolower( $needle ) ) { 
			$found = true; 
		} 
		}    
	return $found; 
    }
    
	/* Check if value is valid
	*/
	public function isValid($value){
	$value = (string) $value;
	$objecttypes = $this->getTypes();
	if(!$this->in_arrayi($value,$objecttypes)) {
	$this->_error(self::NOT_VALID);
	return false;
	}
	return true;
    }

}