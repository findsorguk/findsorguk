<?php
/**
 * A view helper to provide a link url for the analytics page.
 * 
 * @package PAS
 * @uses Zend_View_Helper_Abstract
 * @author dpett
 * @version 
 */

class Pas_View_Helper_AnalyticsLink extends Zend_View_Helper_Abstract {
	
	/**
	 * The delimiter between the string for the url
	 * @var string
	 */
	
	const SLASH = '/';
	
	
	/** 
	 * Function to get the user role and determine whether to proceed
	 * @access public
	 * @return string|false
	 */
	public function getRole(){
	$user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    	return $person->role;
    } else {
    	return false;
    }
    }
    
	/**
	 * 
	 */
	public function analyticsLink() {
		return $this;
	}
	
	/** 
	 * Get the current url of the page
	 * @access private
	 * @return string 
	 */
	private function getCurUrl(){
		return $this->view->curUrl();
	}
	
	/** Get the path of the URL
	 * @access private
	 * @return string
	 */
	private function getPath(){
		$path = parse_url($this->getCurUrl(), PHP_URL_PATH); 
		return  self::SLASH . substr($path, 1);
	}
	
	/** 
	 * Encode the url path
	 * @access private
	 * @return string
	 */
	private function encodePath()
	{
		$raw = base64_encode($this->getPath());
		return $raw;
	}
	
	/** 
	 * Assemble the url for the magic method to use
	 * @access private
	 * @return string|null
	 */
	private function url(){
		if($this->getRole()){
		$params = array(
			'module' 		=> 'analytics',
			'controller' 	=> 'content',
			'action'		=> 'page',
			'url'			=> rawurlencode($this->encodePath())
		);
		$url = $this->view->url($params, 'default', true);
		$html = '<a rel="nofollow" class="btn" href="';
		$html .= $url . '">View analytics <i class="icon-signal"></i></a>';
		return $html;
		} else {
			return '';
		}
	}
	
	public function __toString()
	{
		return $this->url();
	}
}

