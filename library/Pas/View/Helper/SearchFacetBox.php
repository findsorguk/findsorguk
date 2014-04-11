<?php
/**
 *
 * @author dpett
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * SearchFacetBox helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_SearchFacetBox {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * 
	 */
	public function searchFacetBox() {
		// TODO Auto-generated Pas_View_Helper_SearchFacetBox::searchFacetBox() helper 
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

