<?php
/**
 * A validation class for checking for unique usernames
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_Parish extends Zend_Validate_Abstract {
	
	const NOT_VALID = 'notValid';

	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
	protected $_messageTemplates = array(
	self::NOT_VALID => 'That parish does not exist.',
	);

	protected function _getParish($value){
		$parishes = new OsParishes();
		$where[] = $parishes->getAdapter()->quoteInto('osID = ?',$value);
		$parish = $parishes->fetchRow($where);
		return $parish;
	} 


	public function isValid($value){
	$value = (string) $value;
	$parish = $this->_getParish($value);
	if(!$parish) {
	$this->_error(self::NOT_VALID);
	return false;
	}
	return true;
	}
}