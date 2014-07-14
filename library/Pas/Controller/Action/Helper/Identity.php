<?php
/** An action helper for a controller for getting identity from auth storage
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $this->_user = $this->_helper->identity->getPerson();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @example E:\GitHubProjects\findsorguk\app\modules\database\controllers\ArtefactsController.php
 */
class Pas_Controller_Action_Helper_Identity extends Zend_Controller_Action_Helper_Abstract {

    /** The auth class
     * @access protected
     * @var \Zend_Auth
     */
    protected $_auth;

    /** Get the auth instance
     * @access public
     */
    public function init(){
        $this->_auth = Zend_Auth::getInstance();	
    }

    /** Get the user number
     * @access public
     * @return integer $id The id number
     */
    public function getIdentityForForms() {
        if($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            $id = (int)$user->id;
        } else {
        $id = 3;
        }
        return $id;
    }

    /** Get the person's identity
     * @access public
     * @return boolean
     */
    public function getPerson(){
        if($this->_auth->hasIdentity()) {
            return $this->_auth->getIdentity();
        } else {
            return false;
        }	
    }

    /** The direct function to return
     * @access public
     * @return object
     */
    public function direct() {
        return $this->getPerson();
    }
}