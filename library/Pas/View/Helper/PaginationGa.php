<?php
/**
 *
 * @author dpett
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * PaginationGa helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_PaginationGa {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * 
	 */
	public function paginationGa() {
		// TODO Auto-generated Pas_View_Helper_PaginationGa::paginationGa() helper 
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

