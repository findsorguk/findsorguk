<?php
/**
 * A utility class for calling curl requests
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $curl = new Pas_Curl();
 * $curl->setUri('http://finds.org.uk');
 * $curl->getRequest();
 * $code = $curl->getResponseCode();
 * $json = $curl->decodeJson();
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Curl
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example path description
 * 
 */
class Pas_Curl {
    
    /** Curl config options
     * @access protected 
     * @var array
     */
    protected $_config;
    
    /** The uri requested
     * @access protected
     * @var string
     */
    protected $_uri;
    
    /** The response
     * @access protected
     * @var type 
     */
    protected $_response;
    
    /** The client
     * @access public
     * @var \Zend_Http_Client
     */
    protected $_client;
    
    /** Get the client
     * @access public
     * @return \Zend_Http_Client
     */
    public function getClient() {
        $this->_client = new Zend_Http_Client(
                $this->getUri(), 
                $this->getConfig()
                );
        return $this->_client;
    }

    /** Get the response
     * @access public
     * @return object
     */
    public function getResponse() {
        return $this->_response;
    }

    /** Get the user agent
     * @access public
     * @return \Zend_Http_UserAgent
     */
    public function _getUserAgent(){
        $useragent = new Zend_Http_UserAgent();
        return $useragent->getUserAgent();
    }
    
    /** Standard config
     * @access public
     * @return array
     */
    public function getConfig() {
        $this->_config = array(
        'adapter'   => 'Zend_Http_Client_Adapter_Curl',
        'curloptions' => array(
            CURLOPT_POST =>  true,
            CURLOPT_USERAGENT => $this->_getUserAgent(),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_LOW_SPEED_TIME => 1
            ),
	);
        return $this->_config;
    }

    /** Get the uri to use
     * @access public
     * @return string
     */
    public function getUri() {
        return $this->_uri;
    }

    /** Set a different config
     * @access public
     * @param array $config
     * @return \Pas_Curl
     */
    public function setConfig(array $config) {
        $this->_config = $config;
        return $this;
    }

    /** set the uri to query
     * @access public
     * @param string $_uri
     * @return \Pas_Curl
     */
    public function setUri($_uri) {
        $this->_uri = $_uri;
        return $this;
    }

    /** Get the request
     * @access public
     * @return \Pas_Curl
     */
    public function getRequest() {
	$this->_response = $this->getClient()->request();
        return $this;
    }
    
    public function getBody() {
        if($this->getResponseCode() != 200) {
            return $this->getResponseCode();
        } else {
            return $this->getResponse()->getBody();
        }
    }
    /** Get the response code
     * @access public
     * @return string|boolean
     */
    public function getResponseCode() {
        $code = $this->getResponse()->getStatus();
        if($code == 200){
            $status = $code;
        } else {
            $status = 'Http status returned: ' . $code;
        }
        return $status;
    }
    
    /** Decode json response
     * @access public
     * @return array
     */
    public function getJson(){
        return json_decode($this->getBody());
    }
    
}
