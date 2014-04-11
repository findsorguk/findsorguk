<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * TimeSpansGa helper
 *
 * @uses viewHelper Pas_View_Helper_
 */
class Pas_View_Helper_ModulesGa 
	extends Zend_View_Helper_Abstract {
	
	
	protected $_modules = array(
		'all'			=> 'All',
		'database' 		=> 'Database',
		'contacts' 		=> 'Contacts',
		'getinvolved' 	=> 'Get involved',
		'romancoins'	=> 'Roman coins',
		'contacts'		=> 'Contacts',
		'treasure'		=> 'Treasure',
		'research'		=> 'Research',
		'news'			=> 'News',
		'flickr'		=> 'Photos',
		'conservation'	=> 'Conservation',
		'ironagecoins'	=> 'Iron Age coins',
		'medievalcoins'	=> 'Medieval coins',
		'postmedievalcoins' => 'Post Medieval coins',
		'ironagecoins' => 'Iron Age coins',
		'earlymedievalcoins' => 'Early Medieval coins'
		);
	
	protected $_module;
	protected $_action;
	protected $_controller;
	protected $_moduleChoice;
	
	public function __construct()
	{
		$frontController = Zend_Controller_Front::getInstance()->getRequest();
		$this->_module = $frontController->getModuleName();
		$this->_controller = $frontController->getControllerName();
		$this->_action = $frontController->getActionName();
		$this->_moduleChoice = $frontController->getParam('filter');
	}
	
	/**
	 * 
	 */
	public function modulesGa() {
		return $this;
	}

	private function _createUrls()
	{
	$html = '<ul class="nav nav-pills">';
	foreach($this->_modules as $k => $v){
		$html .= '<li class="';
		if($this->_moduleChoice === $k){
			$html .= 'active';
		} elseif(is_null($this->_moduleChoice) && $k === 'all'){
			$html .= 'active';
		}
		$html .= '"><a href="';
		if($k != 'all'){
		$html .= $this->view->url(array(
			'module' => $this->_module,
			'controller' => $this->_controller,
			'action' => $this->_action,
			'filter' => $k),
			'default', false);
		} else {
			$html .= $this->view->url(array(
			'module' => $this->_module,
			'controller' => $this->_controller,
			'action' => $this->_action),
			'default', false);
		}
		$html .= '">' . ucfirst($v);
		$html .= '</a></li>';
	}
	$html .= '</ul>';
	return $html;
	}
	public function __toString(){
		return $this->_createUrls();
	}
}
