<?php
/**
 * A view helper for MP bios via sparql
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
	ini_set('display_errors', '0');     # don't show any errors...
	error_reporting(E_ALL | E_STRICT);  # ...but do log them
	
class Pas_View_Helper_Mpbio 
	extends Zend_View_Helper_Abstract{
	
	protected $_arc;
        
	protected $_config;
	
        protected $_cache;
        
	/** Initialise objects
	 * 
	 */
	public function init() {
	$this->_arc = new Arc2();
	$this->_config = array('remote_store_endpoint' => 'http://dbpedia.org/sparql');
	$this->_cache = Zend_Registry::get('cache');
        
        }
	
	/** Get MP bio from full name
	 * 
	 */
	public function mpbio($fullname) {
	if(!is_null($fullname)) {
	$data = $this->sparqlQuery($fullname);
	if(count($data)) {
	$response = $this->parseResults($data);
	return $this->buildHtml($response);
	} else {
	return NULL;
	}	
	} else {
	return NULL;
	}
	}
	
	/** Perform Spraql
	 * 
	 * @param string $fullname
	 */
	public function sparqlQuery($fullname) {
        $key = md5($fullname . 'mpbio');
	if (!($this->_cache->test($key))) {
	$fullname = str_replace(array('Edward Vaizey','Nicholas Clegg'),
                array('Ed Vaizey', 'Nick Clegg'),$fullname);
	$store = $this->_arc->getRemoteStore($this->_config);
	$name = str_replace(' ','_',$fullname);
	$encoded = urlencode($name);
 	$query = '
	PREFIX owl: <http://www.w3.org/2002/07/owl#>
	PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	PREFIX dc: <http://purl.org/dc/elements/1.1/>
	PREFIX : <http://dbpedia.org/resource/>
	PREFIX dbpedia2: <http://dbpedia.org/property/>
	PREFIX dbpedia: <http://dbpedia.org/>
	PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
	PREFIX dbpedia-owl: <http://dbpedia.org/ontology/>
	SELECT *
	WHERE {
	?mp foaf:name ?name .
	?mp dbpedia-owl:abstract ?abstract.
	?mp dbpedia-owl:thumbnail ?thumb .
	?mp dbpedia2:party ?party .
	?mp dbpedia2:almaMater ?uni .
	FILTER (?name = "' . $fullname . '"@en)
	FILTER langMatches( lang(?abstract), "en") 
	FILTER (?party != "")
	}
	LIMIT 1';
 	
 	$rows = $store->query($query, 'rows');
        $this->_cache->save($rows);
	} else {
	$rows = $this->_cache->load($key);
	}
 	return $rows;
	}
	
	/** Create an array from results
	 * 
	 * @param unknown_type $results
	 */
	public function parseResults($results) {
	$mpdata = array();
	foreach($results as $r) {
	$mpdata['thumbnail'] = $r['thumb'];	
	$mpdata['depiction'] = $r['depiction'];
	$mpdata['abstract'] = $r['abstract'];
	$mpdata['caption'] = $r['caption'];
	$mpdata['uni']= $r['uni'];	
	}	
	return $mpdata;
	}
	
	/** Build the html
	 * 
	 * @param $response
	 */
	public function buildHtml($response) {
	$chunks = split('\. ',$response['abstract']);
	foreach($chunks as $key=>$c){
        $chunks[$key] = ($key%3==0) ? ($c . '.</p><p>') : ($c.'. ');
	}
	$abs = '<p>' . join($chunks) . '</p>';
	$html = '<h3>Dbpedia sourced information</h3>';
	if(array_key_exists('thumbnail',$response)){
	list($w, $h, $type, $attr) = getimagesize($response['thumbnail']);
	$html .= '<img src="'.$response['thumbnail'] . '" alt ="Wikipedia 
            sourced picture" height="' . $h . '" width="'
	. $w . '" class="flow" />';
	}
	$html .= $abs;
	if(array_key_exists('uni',$response)){
	$html .= '<p>Educated: '.rawurldecode(str_replace(array('_', 
            'http://dbpedia.org/resource/'), array(' ',''),$response['uni'])) 
                . '</p>';
	}
	return $html;
	}
}

