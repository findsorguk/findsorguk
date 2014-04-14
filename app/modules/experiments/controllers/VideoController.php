<?php



class Experiments_VideoController extends Pas_Controller_Action_Admin {
	/**
	 * The default action - show the home page
	 */
	protected $_config;
	protected $_cache;
	
	public function init() {
		$this->_helper->_acl->allow('public',NULL);
		$this->_config = Zend_Registry::get('config');
		$this->_cache = Zend_Registry::get('rulercache');
		
    	
    }
	
	public function indexAction() {
	$counties = new OsCounties();
	Zend_Debug::dump($counties->getCounties());
	$districts = new OsRegions();
	Zend_Debug::dump($districts->getRegions());
	$parishes = new OsParishes();
	Zend_Debug::dump($parishes->getParishesToDistrict(9));
	Zend_Debug::dump($parishes->getParishesToCounty(33));
	Zend_Debug::dump($parishes->getParishesToRegion(41426));
	exit;
	}
	
	
}

