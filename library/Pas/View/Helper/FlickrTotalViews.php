<?php
/** A view helper for displaying flickr total views for photos
 * @version 1
 * @since 25 October 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Flickr
 */
class Pas_View_Helper_FlickrTotalViews extends Zend_View_Helper_Abstract {

	protected $_flickr;
	
	/** Create cache object
	 * 
	 */
	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
	}

	/** Get the data from flickr via oauth
	 * 
	 * @param object $flickr
	 */
	public function getFlickr($flickr){
	$this->_flickr = new Pas_Yql_Flickr($flickr);
	if (!$flickr = $this->_cache->load('flickrviews')) {
	$flickr = $this->_flickr->getTotalViews();
	$this->_cache->save($flickr, 'flickrviews');
	}
	return $this->buildHtml($flickr);
	}
	
	/** Get the total views
	 * 
	 * @param $flickr
	 */
	public function flickrTotalViews($flickr) {
	return $this->getFlickr($flickr);
	}
	
	/** Build the Html for display
	 * 
	 * @param array $flickr
	 */
	public function buildHtml($flickr){
	$html = '<h3>Photo Statistics</h3>';
	$html .= '<p>';
	if(array_key_exists('stats' , $flickr)){
	$stats = array();
	foreach($flickr->stats as $k => $v) {
		if($v->views > 0){
		$stats[] = ucfirst($k) . ' views: '. number_format($v->views);
		}
	}
	$html .= implode(' | ', $stats);
	} else {
		$html .= $flickr->message;
	}
	$html .= '</p>';
	echo $html;
	}
	
}

