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
class Pas_View_Helper_DbPediaMintRdf extends Zend_View_Helper_Abstract {
	
	protected $_client;
	
	protected $_cache;
	
	protected $_uri;
	
	const LANGUAGE = 'en';  
	
	
	/**
	 * 
	 */
	public function DbPediaMintRdf () {
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
		EasyRdf_Namespace::set('dbpediaowl', 'http://dbpedia.org/ontology/');
    	EasyRdf_Namespace::set('dbpprop', 'http://dbpedia.org/property/');
    	EasyRdf_Namespace::set('dbpedia', 'http://dbpedia.org/resource/');
		return $data;
	}
	
	protected function _render(){
		$d = $this->getData();
		$html = '';
		if ($d->get('dbpediaowl:thumbnail')){
		$html .= '<a href="' . $d->get('foaf:depiction') . '" rel="lightbox"><img src="' ;
		$html .= $d->get('dbpediaowl:thumbnail');
		$html .= '" class="pull-right"/></a>';
		}
		$html .= '<ul>';
		$html .= '<li>Preferred label: ' . $d->label(self::LANGUAGE) . '</li>';
		$html .= '<li>Geo coords: ' . $d->get('geo:lat') . ',' . $d->get('geo:long') . '</li>';
		$html .= '<li>Definition: ' . $d->get('dbpediaowl:abstract', 'literal', self::LANGUAGE) . '</li>';
		if($d->get('dbpediaowl:elevation')){
		$html .= '<li>Elevation: ' . $d->get('dbpediaowl:elevation') . ' (Max: ' . $d->get('dbpediaowl:maximumElevation') . ' Min: ' . $d->get('dbpediaowl:minimumElevation') . ')</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	
	public function __toString(){
		return $this->_render();
	}
}

