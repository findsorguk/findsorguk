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
class Pas_View_Helper_OsTouchesRdf extends Zend_View_Helper_Abstract {
	
	protected $_client;
	
	protected $_cache;
	
	protected $_uri;
	
	const BASEURI = 'http://data.ordnancesurvey.co.uk/doc/7000000000';	
	
	public function getRole(){
	$user = new Pas_User_Details();
	$person = $user->getPerson();
	if($person){
	return $user->getPerson()->role;
	} else {
		return false;
	}
	}
	
	/**
	 * 
	 */
	public function osTouchesRdf() {
		$this->_client = new Pas_RDF_Client();
		$this->_cache = Zend_Registry::get('cache');
		return $this;
	}
	
	public function setUri( $uri ){
		if(isset($uri)){
			$this->_uri = self::BASEURI . str_pad($uri, 6, '0', STR_PAD_LEFT);
		} 
//		else {
//			throw new Pas_Exception_Url('No uri set');
//		}
		return $this;
	}
	
	protected function getData(){
		$key = 'os' . md5($this->_uri);
		if (!($this->_cache->test($key))) {
		$graph = new EasyRdf_Graph( $this->_uri );
        $graph->load();
        $data = $graph->resource($this->_uri);
		$this->_cache->save($data);
		} else {
		$data = $this->_cache->load($key);
		}
//		EasyRdf_Namespace::set('j.2', 'http://data.ordnancesurvey.co.uk/ontology/spatialrelations/');
		return $data;
	}
	
	protected function _render(){
		$data = $this->getData();
//		$places = $data->all('j.2:touches');
		$html = '';
		return $html;
	}
	
	public function __toString(){
		return $this->_render();
	}
}

