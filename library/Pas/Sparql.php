<?php
/**
 * A class for querying sparql remote endpoints
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://URL name
 * @category   Pas
 * @package    Pas_Sparql
 */
class Pas_Sparql {

    /** The config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;
    
    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The arc2 object
     * @access protected
     * @var \ARC2 
     */
    protected $_arc2;

    /** Constructor
     * @access public
     * @param string $endpoint The Sparql store to query.
     */
    public function __construct( $endpoint){
        $endpoint = $this->checkUrl($endpoint);
        $this->_config = array('remote_store_endpoint' => $endpoint);
        $this->_arc2 = new ARC2();
        $this->_cache = Zend_Registry::get('cache');
    }

    /** Query the end point
     * @access public
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