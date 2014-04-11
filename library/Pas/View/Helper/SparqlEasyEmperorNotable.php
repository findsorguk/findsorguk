<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * SparqlEasy helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_SparqlEasyEmperorNotable extends Zend_View_Helper_Abstract {
	
	protected $_endpoint;
	
	protected $_client;
	
	protected $_id;
	
	protected $_cache;
	/**
	 * 
	 */
	public function sparqlEasyEmperorNotable() {
		$this->_client = new Pas_RDF_Client();
		$this->_endpoint = new EasyRdf_Sparql_Client('http://dbpedia.org/sparql');
		EasyRdf_Namespace::set('category', 'http://dbpedia.org/resource/Category:');
    	EasyRdf_Namespace::set('dbpedia', 'http://dbpedia.org/resource/');
   	 	EasyRdf_Namespace::set('dbo', 'http://dbpedia.org/ontology/');
    	EasyRdf_Namespace::set('dbp', 'http://dbpedia.org/property/');
    	$this->_cache = Zend_Registry::get('cache');
		return $this;
	}
	
	public function setDbpediaID( $id ){
		$this->_id = $id;
		return $this;
	} 
	
	public function getSparqlData(){
		$query = 'SELECT DISTINCT * WHERE { ?x dbo:notableCommander dbpedia:' 
		. $this->_id . '}';
		$key = md5($query);
		if (!($this->_cache->test($key))) {
		$data = $this->_endpoint->query($query);
		$this->_cache->save($data);
		} else {
		$data = $this->_cache->load($key);
		}
		return $data;
	}
	
	protected function _cleaner($string){
			$html = str_replace(array('http://dbpedia.org/resource/', 'Category:',  '_'),array('','',' '), $string);
		return $html;
	}
	
	protected function _wikiLink($string) {
			$cleaned = str_replace(array('http://dbpedia.org/resource/'),array('http://en.wikipedia.org/wiki/'), $string);
			$html = '<a href="' . $cleaned . '">' . urldecode($this->_cleaner($string)) . '</a>';
		return $html;
	}
	
	public function render()
	{
		$dataSparql = $this->getSparqlData();
		if(sizeof($dataSparql) > 0){
		$html = '<h3>Notable commands</h3>';
		$html .= '<ul>';
		foreach($dataSparql as $data){
			$html .='<li>';
			$html .= $this->_wikiLink($data->x);
			$html .= '</li>';
		}
		$html .= '</ul>';
		} else {
			$html = '';
		}
		return $html;
	}	
	
	public function __toString(){
		return $this->render();
	}
	
}

