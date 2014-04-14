<?php



class Experiments_IndexController extends Pas_Controller_Action_Admin {
	/**
	 * The default action - show the home page
	 */
	protected $_oauth;
	protected $_config;
	protected $_cache;
	
	public function init() {
		$this->_helper->_acl->allow('public',NULL);
    	$this->_oauth = new Pas_Yql_Oauth();  
		$this->_config = Zend_Registry::get('config');
		$this->_cache = Zend_Registry::get('rulercache');
		
    	
    }
	
	public function indexAction() {

	}
	
	
}

