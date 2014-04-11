<?php
/**
 * A validation class for checking for valid British National Grid values
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_Url extends Zend_Validate_Abstract {
    
	const INVALID_URL = 'invalidUrl';
 
	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
    protected $_messageTemplates = array(
        self::INVALID_URL   => "'%value%' is not a valid URL.",
    );
 
    /* Check if valid
     * 
     */
    public function isValid($value) {
        $valueString = (string) $value;
        $this->_setValue($valueString);
 
        if (!Zend_Uri::check($value)) {
            $this->_error(self::INVALID_URL);
            return false;
        }
        return true;
    }
}