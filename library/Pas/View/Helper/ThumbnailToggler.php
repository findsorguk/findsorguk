<?php
/**
 * Toggler to set whether search results come back with images or not.
 * Needs rewriting to use toString magic method
 * @uses Zend_View_Helper_Abstract
 * @author dpett
 * @version 1
 * @since 30/4/2013
 * @license GNU Public
 */

/**
 * ResultsQuantityChooser helper
 *
 */
class Pas_View_Helper_ThumbnailToggler extends Zend_View_Helper_Abstract{
	
	/**
	 * Request variable
	 * @var object
	 * @access protected
	 */
	protected $_request; 
	
	/** 
	 * Construct the request object
	 * @access public
	 */
	public function __construct(){
		$this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	}
	
	/** 
	 * Return html
	 * @return string
	 * @access public
	 */
	public function thumbnailToggler() {
	$html = '<div>Only results with images: ';
	$active = 'success';
	$off = 'inverse';
	if(array_key_exists('thumbnail', $this->_request)){
		$thumbnail = $this->_request['thumbnail'];
	} else {
		$thumbnail = NULL;
	}
	$onRequest = $this->_request;
	$onRequest['thumbnail'] = 1; 
	$offRequest = $this->_request;
	unset($offRequest['thumbnail']);
	
	if(!is_null($thumbnail)){
	$html .= '<a class="btn btn-small btn-' . $active . '" href="' . $this->view->url($onRequest,'default',true) .'">on</a> ';
	$html .= '<a class="btn btn-small btn-' . $off . '" href="' . $this->view->url($offRequest,'default',true) .'">off</a>';
	} else {
	$html .= '<a class="btn btn-small btn-' . $off . '"  href="' . $this->view->url($onRequest,'default',true) .'">on</a> ';
	$html .= '<a class="btn btn-small btn-' . $active . '"  href="' . $this->view->url($offRequest,'default',true) .'">off</a>';
	}
	$html .= '</div>';
	return $html;
	}
}