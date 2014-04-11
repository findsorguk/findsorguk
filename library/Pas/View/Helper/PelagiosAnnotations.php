<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * PelagiosAnnotations helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_PelagiosAnnotations extends Zend_View_Helper_Abstract{
	
	
	const BASEURI = 'http://pelagios.dme.ait.ac.at/api/places/'; 
	
	const SUFFIX = '/datasets.json';
	
	const PLEIADESURI = 'http://pleiades.stoa.org/places/';
	
	protected $_cache;
	
	protected $_uri;
	
	/**
	 * 
	 */
	public function pelagiosAnnotations() {
		$this->_cache = Zend_Registry::get('cache');
		return $this;
	}
	
	public function setPleiadesPlace( $place ) {
		if(isset( $place )){
			$this->_uri = urlencode(self::PLEIADESURI . $place);
		} else {
			throw new Pas_Exception_Url('No uri has been provided to query');
		}
		return $this;
	}
	
	protected function _getData() {
		$key = md5($this->_uri . 'pelagios');
		if (!($this->_cache->test($key))) {
		$config = array(
	    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	    'curloptions' => array(CURLOPT_POST =>  true,
							   CURLOPT_USERAGENT =>  'findsorguk',
							   CURLOPT_FOLLOWLOCATION => true,
							   CURLOPT_RETURNTRANSFER => true,
							   ),
		);	
		$client = new Zend_Http_Client(self::BASEURI . $this->_uri . self::SUFFIX, $config);
		$response = $client->request();
		$data = $response->getBody();
		$json = json_decode($data);
		$newJson = array();
		foreach($json as $js){
			$js->pleiades = $this->_uri;
			$newJson[] = $js;
		}
		$this->_cache->save($newJson);
		} else {
		$newJson = $this->_cache->load($key);
		}
		return $newJson;
	}
	
	
	public function __toString(){
		$html = '<h3>Other resources via Pelagios</h3>';
		if($this->_getData()){
			$html .= '<ul>';
			$html .= $this->view->partialLoop('partials/numismatics/pelagios.phtml', $this->_getData());
			$html .= '</ul>';
			$html .= '<p>Data provided from the <a href="http://pelagios-project.blogspot.com/" title="read about Pelagios" >Pelagios Project</a></p>';
		} else {
			$html .= '<p>No annotations found</p>';
		}
		return $html;
	}
}

