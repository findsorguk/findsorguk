<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * NomismaRdf helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_NomismaRdf extends Zend_View_Helper_Abstract {
	
	protected $_client;
	
	protected $_cache;
	
	protected $_uri;
	
	const LANGUAGE = 'en';  
	
	
	/**
	 * 
	 */
	public function nomismaRdf() {
		$this->_client = new Pas_RDF_Client();
		$this->_cache = Zend_Registry::get('cache');
		return $this;
	}
	
	public function setUri( $uri ){
		if(isset($uri)){
			$this->_uri = $uri;
		} else {
			throw new Pas_Exception_Url('No uri set');
		}
		return $this;
	}
	
	protected function getData(){
		$key = md5($this->_uri);
		if (!($this->_cache->test($key))) {
		$graph = new EasyRdf_Graph( $this->_uri );
	        $graph->load();
        	$data = $graph->resource($this->_uri);
		$this->_cache->save($data);
		} else {
		$data = $this->_cache->load($key);
		}
		return $data;
	}
	
	protected function _render(){
		$data = $this->getData();
		$html = '<img src="http://maps.google.com/maps/api/staticmap?center=' .  $data->get('geo:lat') 
		. ',' . $data->get('geo:long') . '&zoom=5&size=200x200&maptype=hybrid&markers=color:green|label:G|'
		.  $data->get('geo:lat') . ',' . $data->get('geo:long') . '&sensor=false" class="stelae"/>';
		$html .= '<ul>';
//		$html .= '<li>Preferred label: ' . $data->label(self::LANGUAGE) . '</li>';
		foreach($data->all('skos:prefLabel') as $labels){
			$html .= '<li>Preferred label: ' . $labels->getValue() . ' (' . $labels->getLang() . ')</li>';
		}
		$html .= '<li>Geo coords: ' . $data->get('geo:lat');
		$html .= ',';
		$html .= $data->get('geo:long');
		$html .= '</li>';
		$html .= '<li>Definition: ' . $data->get('skos:definition');
		$html .= '</li></ul>';
		return $html;
	}
	
	public function __toString(){
		return $this->_render();
	}
}

