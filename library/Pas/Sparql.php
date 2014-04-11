<?php
/**
 * A class for querying sparql remote endpoints
 * 
 * @category   Pas
 * @package    Sparql
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Pas_Sparql {

	protected $_config, $_cache;
	
	protected $_arc2;
	
		
	/** Constructor
	 * 
	 * @param string $endpoint The Sparql store to query.
	 */
	public function __construct( $endpoint){
		
	$endpoint = $this->checkUrl($endpoint);
	$this->_config = array('remote_store_endpoint' => $endpoint);
	$this->_arc2 = new ARC2();
	$this->_cache = Zend_Registry::get('cache');
	}
	
	/** Query the end point
	 * 
	 * @param string $q Send a query string in SPARQL
	 * @param boolean $cache Define whether to call with cache
	 */
	public function queryEndpoint( $q, $cache = false ){
		if($cache == true) {
			return $this->cachedQuery($q);
		} else {
			return $this->uncachedQuery($q);
		}
	}
	
	/** Perform a cached query for sparql using ARC2
	 * @access private
	 * @param string $q SPARQL query to perform
	 * @return array
	 */
	private function cachedQuery($q){
	$id = md5($q);
    if ((!($this->_cache->test($id))) || (!$this->cache_result)) {	
	$retrieve = $this->_arc2->getRemoteStore($this->_config);
	$response = $retrieve->query($q,'rows');
	$this->_cache->save($response);
	return $response;
    } else {
    return $this->_cache->load($id);
    }
	}

	/** Perform an uncached query for sparql using ARC2
	* @access private
	* @param string $q SPARQL query to perform
	* @return array
	*/
	private function uncachedQuery($q){
	$retrieve = $this->_arc2->getRemoteStore($this->_config);
	Zend_Debug::dump($retrieve);
	$response = $retrieve->query($q,'rows');
	return $response;	
	}
	
	/** Check that the URL is valid
	 * @access private
	 * @param string $url to validate
	 * @return string $url
	 */
	private function checkUrl($url) {
	if (!Zend_Uri::check($url)) {
    	throw new Pas_Exception_Url(self::INVALIDURL);
    }
	return $url;
	}
}