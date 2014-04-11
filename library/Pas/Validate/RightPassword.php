<?php
/**
 * A validation class for checking for valid passwords
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */ 
class Pas_Validate_RightPassword extends Zend_Validate_Abstract {
	
	const NOT_VALID = 'notValid';
	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
	protected $_messageTemplates = array(
	self::NOT_VALID => 'Oh dear, wrong password! What did you use to login?');

	/**
 	* Validate the password
 	*/
	public function isValid($value) {
    $auth = Zend_Registry::get('auth');
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	$username = $user->username;
	} else {
		throw new Exception('You salty seadog, vamoosh and go to the right place!');
	}
	$users = new Users();
	$users = $users->fetchRow($users->select()->where('username = ?', $username));
		
	$config = Zend_Registry::get('config');
	$salt = $config->auth->salt;
	$password = sha1($salt.$value);
    if ($users->password != $password) {
	$this->_error(self::NOT_VALID);
    return false;
    }
    return true;
    }

}