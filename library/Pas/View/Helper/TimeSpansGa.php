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
class Pas_View_Helper_TimeSpansGa 
	extends Zend_View_Helper_Abstract {
	
	
	protected $_timespans = array(
		'today'			=> 'today',
		'yesterday'		=> 'yesterday',
		'this week' 	=> 'thisweek',
		'last week' 	=> 'lastweek',
		'this month' 	=> 'thismonth',
		'last month' 	=> 'lastmonth',
		'this year' 	=> 'thisyear',
		'last year' 	=> 'lastyear'
		);
	
	protected $_module;
	protected $_action;
	protected $_controller;
	protected $_timeSpan = 'thisweek';
	
	public function __construct()
	{
		$frontController = Zend_Controller_Front::getInstance()->getRequest();
		$this->_module = $frontController->getModuleName();
		$this->_controller = $frontController->getControllerName();
		$this->_action = $frontController->getActionName();
		$this->_timeSpan = $frontController->getParam('timespan');
	}
	
	/**
	 * 
	 */
	public function timeSpansGa() {
		return $this;
	}

	private function _createUrls()
	{
	$html = '<ul class="nav nav-pills">';
	foreach($this->_timespans as $k => $v){
		$html .= '<li class="';
		if($this->_timeSpan === $v){
			$html .= 'active';
		} elseif(is_null($this->_timeSpan) && $v === 'thisweek'){
			$html .= 'active';
		}
		$html .= '"><a href="';
		$html .= $this->view->url(array(
			'module' => $this->_module,
			'controller' => $this->_controller,
			'action' => $this->_action,
			'timespan' => $v),
			'default', false);
		$html .= '">' . ucfirst($k);
		$html .= '</a></li>';
	}
	$html .= '</ul>';	
	return $html;
	}
	public function __toString(){
		return $this->_createUrls();
	}
}
