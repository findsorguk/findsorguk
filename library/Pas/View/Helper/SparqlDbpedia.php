<?php
class Pas_View_Helper_SparqlDbpedia extends Zend_View_Helper_Abstract {

		protected $_arc;
		protected $_config;
		protected $_cache;
		protected $_name;
	
        public function sparqlDbpedia($name) {
        	if(is_string($name)){
        		$this->_config = array('remote_store_endpoint' => 'http://live.dbpedia.org/sparql');
        		$this->_name = $name;
        		$this->_cache = Zend_Registry::get('cache');
            return $this;
        	} else {
        		return false;
        	}
        }

        public function queryDbpedia()
        {
        	$query = 	'PREFIX owl: <http://www.w3.org/2002/07/owl#>
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
						{
						?person foaf:isPrimaryTopicOf <http://en.wikipedia.org/wiki/' . $this->_name . '> .
						?person  <http://dbpedia.org/ontology/abstract> ?abstract .
						?person  dbpedia2:name ?name .
						
						OPTIONAL {?person  foaf:depiction ?depiction}.
						OPTIONAL {?person  dbpedia-owl:thumbnail ?thumb} .
						OPTIONAL {?person  dbpedia2:imgw ?imgw} .
						OPTIONAL {?person  dbpedia2:successor ?successor} .
						OPTIONAL {?person  dbpedia-owl:spouse ?spouse} .
						OPTIONAL {?person  dbpedia2:father ?father} .
						OPTIONAL {?person  dbpedia2:mother ?mother} .
						OPTIONAL {?person  dbpedia2:birthDate ?birthDate}.
						OPTIONAL {?person  dbpedia2:deathDate ?deathDate}.
						OPTIONAL {?person  dbpedia2:deathPlace ?placeofDeath }.
						OPTIONAL {?person  dbpedia-owl:knownFor ?knownFor} .
						FILTER langMatches( lang(?abstract), \'en\')
						FILTER langMatches( lang(?name), \'en\')}
						
						}
						LIMIT 1';
				$key = md5($query);
				if (!($this->_cache->test($key))) {
        		$store = ARC2::getRemoteStore($this->_config);
        		$rows = $store->query($query, 'rows');
				$this->_cache->save($rows);
				} else {
				$rows = $this->_cache->load($key);
				}
        		return $rows;
        }
       

        public function render() {
        	$dataRaw = $this->queryDbpedia();
        	
        	$html = '';
        	$data = $dataRaw[0];
        	if(array_key_exists('thumb', $data)){
        		$html .= '<img src="' . $data['thumb'] . '" class="pull-right" />';
        	}
			if(array_key_exists('abstract', $data)){
        		$html .= utf8_decode($data['abstract']);
        	}
        	
        	if(array_key_exists('birthDate', $data)){
        		$html .= '<h3>Dates and places</h3>';
        		$html .= '<ul><li>Date of birth: ' . $data['birthDate'] .'</li>';
        		$html .= '<li>Date of death: ' . $data['deathDate'] . '</li>';
        	}
        	if(array_key_exists('placeofDeath', $data)) {
        		$html .= '<li>Place of Death: ' . strip_tags(rawurldecode(str_replace(array('http://dbpedia.org/resource/','_'),array('',' '),$data['placeofDeath']))) . '</li>';
        	}
        	
        	$html .= '</ul>';
        	
        	
        	foreach ( $dataRaw as $d ){
        		if(array_key_exists('knownFor', $d)){
        			$html .= '<h3>Known for</h3>';
        			$html .= strip_tags(rawurldecode(str_replace(array('http://dbpedia.org/resource/','_'),array('',' '),$d['knownFor'])));
        		}
        	}
  		$html .= '<h2>Semantic extraction via OpenCalais</h2>';
  		$key = md5('ocSem' . $this->_name);
		if (!($this->_cache->test($key))) {
		$oc = new Pas_Service_OpenCalais_Tagger(); 
		$entities = $oc->getEntities($html);
		$this->_cache->save($entities);
				} else {
				$entities = $this->_cache->load($key);
				}
		foreach($entities as $paramName => $value){
 		 $html .= '<strong>' . $paramName . '</strong>: ';
 		 foreach($value as $k => $v){
 		 	$html .= $v . ', ';
 		 }
 		 $html .= '<br />';
		}
        	return $html;
        }

        public function __toString() {
            return $this->render();
        }
}