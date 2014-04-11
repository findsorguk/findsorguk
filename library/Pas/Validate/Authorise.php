<?php
/**
 * A validation class for checking if a user is authorised to login
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_Authorise extends Zend_Validate_Abstract {

	protected $_authAdapter;
	
	protected $_config;
	
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	
	}
	const NOT_AUTHORISED = 'notAuthorised';
	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
    protected $_messageTemplates = array(
        self::NOT_AUTHORISED => 'No users with those details exist or your password is incorrect'
    );

    public function getAuthAdapter() {
        return $this->_authAdapter;
    }

    public function isValid($value, $context = null) {
	$value = (string) $value;
	$this->_setValue($value);
    if (is_array($context)) {
		if (!isset($context['password'])) {
			return false;
		}
	}

	$dbAdapter = Zend_Registry::get('db');
	$this->_authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
	$this->_authAdapter->setTableName('users')
		->setIdentityColumn('username')
		->setCredentialColumn('password');
	// get "salt" for better security
	$salt = $this->_config->auth->salt;
	$password = sha1($salt . $context['password']);
        
	$this->_authAdapter->setIdentity($value);
	$this->_authAdapter->setCredential($password);
	$auth = Zend_Auth::getInstance();
	$result = $auth->authenticate($this->_authAdapter);
	if (!$result->isValid()) {
		$this->_error(self::NOT_AUTHORISED);
		return false;
		}
	//Updated the user table - this needs moving to the users model
	$users = new Users();
	$updateArray = array(
		'visits' => new Zend_Db_Expr('visits + 1'), 
		'lastLogin' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm')
	);
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('username = ?', $value);
	$users->updateVisits($updateArray, $where);
		//Update login table needs moving to the login model
	$logins = new Logins();
	$data['loginDate'] =  Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
	$data['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
	$data['ipAddress'] = $_SERVER['REMOTE_ADDR'];
	$data['username'] = $value;
	$insert = $logins->insert($data);
	return true;
    }
}