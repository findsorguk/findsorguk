<?php
/** A validation class for checking for valid passwords
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://URL name
 * @category   Pas
 * @package    Pas_Validate
 * @example /app/forms/ChangePasswordForm.php
 */ 
class Pas_Validate_RightPassword extends Zend_Validate_Abstract {
	
    /** The not valid constant
     * 
     */
    const NOT_VALID = 'notValid';
    /** Validation failure message template definitions
    * @access protected
    * @var array
    */
    protected $_messageTemplates = array(
        self::NOT_VALID => 'Oh dear, wrong password! What did you use to login?'
            );

    /** Validate the password
     * @access public
     * @param string $value
     * @return boolean
     * @throws Exception
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