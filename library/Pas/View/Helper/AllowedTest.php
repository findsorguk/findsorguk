<?php
/**
 * A view helper that checks if admin and allows the string to be printed
 * Absolute rubbish view helper, needs replacing
 * 
 * An example of use:
 * <code>
 * <?php
 * echo $this->allowedTest()->setString($string);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @uses Zend_Auth
 * 
 */
class Pas_View_Helper_AllowedTest extends Zend_View_Helper_Abstract {

    /** The string to print
     * @access protected
     * @var string
     */
    protected $_string;
    
    /** The auth object
     * @access protected
     * @var Zend_Auth
     */
    protected $_auth;

    /** The role of the user
     * @access protected
     * @var string
     */
    protected $_role;
    
    /** The allowed roles
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin');
    
    /** The user object
     * @access public
     * @var object
     */
    protected $_user;
    
    /** Get the auth object
     * @access public
     * @return \Zend_Auth
     */
    public function getAuth() {
        return $this->_auth = Zend_Auth::getInstance();
    }
    
    /** Get the user's identity
     * @access public
     * @return \Zend_Auth
     */
    public function getUser() {
        $this->_user = $this->getAuth()->getIdentity();
        return $this->_user;
    }

    /** Get the role
     * @access public
     * @return string
     */
    public function getRole() {
        $this->_role = $this->getUser()->role;
        return $this->_role;
    }

    /** Get the string to print
     * @access public
     * @return string
     */
    public function getString() {
        return $this->_string;
    }

    /** Set the string
     * @access public
     * @param type $string
     * @return \Pas_View_Helper_AllowedTest
     */
    public function setString($string) {
        $this->_string = $string;
        return $this;
    }

    /** The allowed test function
     * @access public
     * @return \Pas_View_Helper_AllowedTest
     */
    public function allowedTest() {
        return $this;
    }
    
    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        if ($this->getUser()) { 
            if (in_array($this->getRole(),$this->_higherLevel)) {
                return $this->getString();
            }
        } else {
            return '';
        }
    }

}