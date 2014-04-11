<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** A series of method calls to the excellent mysociety produced MapIt api.
 * @category Pas
 * @package Pas_Geo_Mapit
 * @since 6/2/12
 * @version 1
 * @copyright Daniel Pett, British Museum
 * @license GNU
 * @author Daniel Pett
 * @see http://mapit.mysociety.org/ for full documentation of api
 */
class Pas_Geo_Mapit {

    /** The base mapit url
     *
     */
    const MAP_IT_URI = 'http://mapit.mysociety.org/';


    /** Set up the cache
        *
        * @var type
        */
    protected $_cache;

    /** Generally default to json for response
     *
     * @var string
     */
    protected $_format = 'json';

    /** The url to call
     *
     * @var string
     */
    protected $_url = null;

    protected $_generation = null;

    protected $_filter = null;

    /** Construct the cache. If an api key is needed, this can be set here
     * when it is introduced
     */
    public function __construct(){
    $frontendOptions = array(
        'lifetime' => 31556926, // Monster year cache as it won't change that much
        'automatic_serialization' => true
        );
    $backendOptions = array(
    		'cache_dir' => CACHE_PATH . '/mapit'
    );
    $this->_cache = Zend_Cache::factory(
            'Output',
            'File',
            $frontendOptions,
            $backendOptions
    );
    }

    /** Perform a curl request based on url provided
    * @access public
    * @uses Zend_Cache
    * @uses Zend_Json_Decoder
    * @param string $method
    * @param array $params
    */
    public function get($method, array $params) {
    $url = $this->setUrl($method,$params);
    $key = md5($url);
    if (!($this->_cache->test($key))) {
    $config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
    CURLOPT_POST =>  true,
    CURLOPT_USERAGENT =>  $this->_getUserAgent(),
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_LOW_SPEED_TIME => 1,
    ),
    );

    $client = new Zend_Http_Client($url, $config);
    $response = $client->request();

    $data = $response->getBody();
    $this->_cache->save($data);
    } else {
    $data = $this->_cache->load($key);
    }
    if($this->_format === 'json'){
        return $this->_decoder($data);
    } else {
       return $data;
    }
    }


    /** Get the user Agent for sending curl response
     * @access protected
     * @return string
     */
    protected function _getUserAgent(){
    $useragent = new Zend_Http_UserAgent();
    return $useragent->getUserAgent();

    }

    /** Decode the json response as an object
     * @access protected
     * @param string $data
     * @return object
     */
    protected function _decoder($data){
        return Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
    }


    /** Build a url string
        * @param string $method The method to use
        * @param array $params
        */
    public function setUrl($method, array $params){
        if(is_array($params)){
        $params = array_filter($params);

        $this->_url = self::MAP_IT_URI . $method . '/' . implode('/',$params);
        if(isset($this->_format) && $this->_format !== 'json'){
        	$this->_url = $this->_url . '.' . $this->_format;
        }
        if(isset($this->_generation)){
            $this->_url = $this->_url . $this->_generation;
        }

        if(isset($this->_filter)){
            $this->_url = $this->_url . $this->_filter;
        }

        return $this->_url;
        } else {
            throw new Pas_Twfy_Exception('Parameters have to be an array',500);
        }
    }

    public function getUrl(){
    	return $this->_url;
    }


}
