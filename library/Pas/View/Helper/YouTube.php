<?php
/**
 *
 * @author dpett
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * YouTube helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_YouTube {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * 
	 */
	public function youTube() {
		// TODO Auto-generated Pas_View_Helper_YouTube::youTube() helper 
		return null;
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}

