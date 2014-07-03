<?php
/* An overiew of the GA api and what we can read
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 */
class Analytics_IndexController extends Pas_Controller_Action_Admin {
    
    public function init() {
 	$this->_helper->_acl->allow(null); 
	$this->view->username = $this->_helper->config()->webservice->google->username;
	$this->view->password = $this->_helper->config()->webservice->google->password;
	$this->view->profile = 25726058;
	$this->_service = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
    }
    
    public function indexAction() {
    }
}

