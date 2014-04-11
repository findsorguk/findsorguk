<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * PleiadesFlickrImages helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_FlickrImageSearch extends Zend_View_Helper_Abstract {
	
	protected $_cache;
	
	protected $_api;
	
	protected $_flickr;
	
	protected $_config;
	
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_cache = Zend_Registry::get('cache');
	$this->_flickr = $this->_config->webservice->flickr;
	$this->_api	= new Pas_Yql_Flickr($this->_flickr);
	}
	
	/**
	 * 
	 */
	public function flickrImageSearch($term) {
		if(isset($term)) {
		$photos = $this->_api->searchPhotos($term,4);
				if(!is_array($photos->photo)){
			$photos->photo = array($photos->photo);
		}
		if($photos->photo){
		$html = '<div class="row-fluid"><h2>Flickr images via YQL</h2><h3>Photos associated with ' . $term .'</h3>';
		if(is_array($photos->photo)){
		$html .= $this->view->partialLoop('partials/flickr/favourite.phtml', $photos->photo);
		} else {
		$html .= $this->view->partial('partials/flickr/favourite.phtml', $photos->photo);
		}
		$html .= '</div>';
		return $html;
		}
		} else {
		return null;
		}
	}
	
}

