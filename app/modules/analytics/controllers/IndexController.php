<?php
/**  An overiew of the GA api and what we can read
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 * @uses Zend_Gdata_Analytics
 */
class Analytics_IndexController extends Pas_Controller_Action_Admin {
    
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
 	$this->_helper->_acl->allow(null); 
	$this->view->username = $this->_helper->config()->webservice->google->username;
	$this->view->password = $this->_helper->config()->webservice->google->password;
	$this->view->profile = 25726058;
	$this->_service = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
    }
    
    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        //Magic in view
    }
}

