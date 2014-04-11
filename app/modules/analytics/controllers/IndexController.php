<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Katiebear
 */
class Analytics_IndexController extends Pas_Controller_Action_Admin {
    
    protected $_ID;
    
    protected $_pword;
    
    protected $_service;

    
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

