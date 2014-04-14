<?php



class Experiments_MiddleeastController extends Pas_Controller_Action_Admin {
	
	public function init() {
		$this->_helper->_acl->allow('public',NULL);
		$this->_config = Zend_Registry::get('config');
		$this->_cache = Zend_Registry::get('rulercache');
    }
	
	public function indexAction()
	{
		
	}
	
	public function personAction()
	{
		if($this->_getParam('called',false)){
			
		} else {
			throw new Pas_Exception_BadJuJu('No name has been called', 500);
		}
	}
	
}

