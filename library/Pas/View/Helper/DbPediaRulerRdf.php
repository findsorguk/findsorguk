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
class Pas_View_Helper_DbPediaRulerRdf extends Zend_View_Helper_Abstract {
	
	protected $_client;
	
	protected $_cache;
	
	protected $_uri;
	
	const LANGUAGE = 'en';  
	
	
	/**
	 * 
	 */
	public function DbPediaRulerRdf () {
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
    	EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
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
	
	protected function _render(){
		$d = $this->getData();
		$html = '<h3>Information from Wikipedia</h3>';
		if ($d->get('dbpediaowl:thumbnail')){
		$html .= '<img src="' ;
		$html .= $d->get('dbpediaowl:thumbnail');
		$html .= '" class="pull-right stelae"/>';
		}
		$html .= '<ul>';
		$html .= '<li>Preferred label: ' . $d->label(self::LANGUAGE) . '</li>';
		$html .= '<li>Full names: <ul>';
		foreach($d->all('foaf:name', 'literal') as $name){
			$html .= '<li>';
			$html .= $name->getValue();
			$html .= '</li>';
		}
		$html .= '</li></ul>';
		$html .= '<li>Title:' . implode(', ', $d->all('dbpediaowl:title', 'literal') ) . '</li>';
//		$html .= '<li>Length of reign:' . $d->get('dbpprop:reign', 'literal'). ' (years or months)</li>';
		$html .= '<li>Predecessor: ' . $this->_cleaner(implode(', ',$d->all('dbpediaowl:predecessor', 'resource') )) . '</li>';
		$html .= '<li>Successor: ' . $this->_cleaner(implode(', ',$d->all('dbpediaowl:successor', 'resource') )) . '</li>';
		$html .= '<li>Definition: ' . $d->get('dbpediaowl:abstract', 'literal', self::LANGUAGE) . '</li>';

		$html .= '<li>Parents: <ul>';
			$html .= '<li>Father: ';
			$html .= $this->_wikiLink($d->get('dbpprop:father', 'resource'));
			$html .= '</li>';
			$html .= '<li>Mother: ';
			$html .= $this->_wikiLink($d->get('dbpprop:mother', 'resource'));
			$html .= '</li>';
		$html .= '</ul></li>';
		$birth = $d->all('dbpprop:birthPlace', 'resource');
		$newBirth = array();
		foreach($birth as $nb){
			$newBirth[] = $this->_wikiLink($nb);
		}
		$html .= '<li>Birth place: ' .  implode(', ', $newBirth) . '</li>';
		$death = $d->all('dbpprop:deathPlace', 'resource');
		$reBirth = array();
		foreach($death as $reb){
			$reBirth[] = $this->_wikiLink($reb);
		}
		$html .= '<li>Death place: ' .  implode(', ', $reBirth) . '</li>';
		$html .= '<li>Spouse: <ul>';
		foreach($d->all('dbpprop:spouse', 'resource') as $name){
			$html .= '<li>';
			$html .= $this->_wikiLink($name);
			$html .= '</li>';
		}
		$html .= '</ul></li>';
		$html .= '<li>Other title(s): <ul>';
		$titles = array();
		foreach($d->all('dbpprop:title') as $name){
			$titles[] = urldecode($this->_cleaner($name));
		}
		$new = array_unique($titles, SORT_STRING);
		foreach($new as $n){
			if(strlen($n) > 4){
			$html .= '<li>';
			$html .= $n;
			$html .= '</li>';
			}
		}	
		$html .= '</ul></li>';
		$html .= '<li>Came After: <ul>';
		foreach($d->all('dbpprop:after') as $name){
			if(strlen($name) > 4){
			$html .= '<li>';
			$html .= $this->_cleaner($name);
			$html .= '</li>';
			}
		}
		$html .= '</ul></li>';
		$html .= '<li>Came before: <ul>';
		foreach($d->all('dbpprop:before') as $name){
			if(strlen($name) > 4){
			$html .= '<li>';
			$html .= $this->_cleaner($name);
			$html .= '</li>';
			}
		}
		$html .= '</ul></li>';
		$subjects = $d->all('dcterms:subject', 'resource');
		$html .= '<li>Subjects on wikipedia: <ul>';
		foreach($subjects as $subject){
		$html .= '<li>';
			$html .= $this->_wikiLink($subject);
			$html .= '</li>';
		}
		$html .= '</ul></li>';
		$html .= '</ul>';
		return $html;
	}
	
	public function __toString(){
		return $this->_render();
	}
	
}



