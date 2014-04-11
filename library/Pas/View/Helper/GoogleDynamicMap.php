<?php

/**
 * GoogleDynamicMap helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_GoogleDynamicMap extends Zend_View_Helper_Abstract {
	
	protected $_key;
	
	protected $_config;
	
	protected $_region;
	
	protected $_dynamicUrl = 'http://maps.googleapis.com/maps/api/js';
	
	protected $_clusterUrl = '/js/markerclusterer.js';
	
	protected $_nlsUrl = 'http://nls.tileserver.com/api.js';
	
	protected $_stamenUrl = 'http://maps.stamen.com/js/tile.stamen.js';

	protected $_version;
	
	protected $_sensor = 'false';
	
	protected $_type = 'text/javascript';
	
	public function __construct() {
		$this->_config = Zend_Registry::get('config');
		if($this->_key == null){
			$this->_key = $this->_config->webservice->googlemaps->apikey;
		}
		if($this->_region == null){
			$this->_region = $this->_config->webservice->googlemaps->region;
		}
		if($this->_version == null){
			$this->_version = $this->_config->webservice->googlemaps->version;
		}
	}
	/**
	 * 
	 */
	public function googleDynamicMap($sensor = null, $clusterer = null, $nlsMap = null, $stamen = null) {
	if(!is_null($sensor)){
		$this->_sensor = 'true';
	} 
	$url = $this->_dynamicUrl . '?version=' . $this->_version;
	$url .= '&key=' . $this->_key;
	$url .= '&sensor=' . $this->_sensor;
	$this->view->inlineScript()->appendFile($url, $this->_type);
	if(!is_null($clusterer)){
	$this->view->inlineScript()->appendFile($this->_clusterUrl, $this->_type);
	}
	if(!is_null($nlsMap)){
	$this->view->inlineScript()->appendFile($this->_nlsUrl, $this->_type);
	}
	if(!is_null($stamen)){
	$this->view->inlineScript()->appendFile($this->_stamenUrl, $this->_type);	
	}
	}
	
}

