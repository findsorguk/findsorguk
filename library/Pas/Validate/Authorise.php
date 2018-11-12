<?php

/**
 * A validation class for checking if a user is authorised to login
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Pas_Validate
 * @subpackage Authorise
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_Validate_Authorise extends Zend_Validate_Abstract
{

    /** The auth adapter
     * @access protected
     * @var \Zend_Auth_Adapter_DbTable
     */
    protected $_authAdapter;

    /** The config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;

    /** Construct the config
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_config = Zend_Registry::get('config');
    }

    /** The not authorised constant
     *
     */
    const NOT_AUTHORISED = 'notAuthorised';

    /** The message templates
     * @access protected
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_AUTHORISED => 'No users with those details exist or your password is incorrect'
    );

    /** Get the auth adapter
     * @access public
     * @return \Zend_Auth_Adapter_DbTable
     */
    public function getAuthAdapter()
    {
        return $this->_authAdapter;
    }

    /** Check if valid
     * @access public
     * @param string $value
     * @param string $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
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
        $data['loginDate'] = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
        $data['userAgent'] = substr($_SERVER['HTTP_USER_AGENT'],0,254);
        $data['ipAddress'] = $_SERVER['REMOTE_ADDR'];
        $data['username'] = $value;
        return $logins->insert($data);
    }
}
