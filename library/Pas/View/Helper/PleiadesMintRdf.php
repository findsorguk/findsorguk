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
class Pas_View_Helper_PleiadesMintRdf extends Zend_View_Helper_Abstract {
	
	protected $_client;
	
	protected $_cache;
	
	protected $_uri;
	
	const LANGUAGE = 'en';  
	const URI = 'http://pleiades.stoa.org/places/';
	const SUFFIX = '/rdf';
	
	/**
	 * 
	 */
	public function PleiadesMintRdf () {
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
		$graph = new EasyRdf_Graph( self::URI . $this->_uri . self::SUFFIX );
        $graph->load();
        $data = $graph->resource( self::URI . $this->_uri );
		$this->_cache->save($data);
		} else {
		$data = $this->_cache->load($key);
		}
		EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
		EasyRdf_Namespace::set('pleiades', 'http://pleiades.stoa.org/places/vocab#');
		return $data;
	}
	
	protected function _render(){
		$pl = $this->getData();
		$html = '';
		$html .= '<ul>';
		$html .=  '<li>Place name: ' . $pl->get('dcterms:title') . '</li>';
		foreach ($pl->all('skos:altLabel','literal') as $term){
		$html .= '<li>Alterative label: ' . $term->getValue() . '</li>';
		}
		$html .= '<li>Description: ' . $pl->get('dcterms:description') . '</li>';
		$html .= '<li>Subjects: '; 
		$subjects = array();
		foreach ($pl->all('dcterms:subject','literal') as $term){
		$subjects[] = $term->getValue() ;
		}
		$html .= implode(', ',$subjects);
	 	$html .='</li>';
	 	$html .= '<li>Referenced:'; 
	 	$html .= '<ul>';
		foreach ($pl->all('dcterms:bibliographicCitation','literal') as $term){
		$html .= '<li>' . $term->getValue() . '</li>';
		}
		$html .= ' </ul>';
		$html .= '</li>';
		$html .= '</ul>';
		return $html;
	}
	
	public function __toString(){
		return $this->_render();
	}
}

