<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Base class for interfacing with the they work for uou api
 * @see theyworkforyou
 * @since 1/2/2012
 * @uses Zend_Cache
 * @uses Zend_Http_Client
 * @category Pas
 * @package Pas_Twfy
 * @version 1
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @license GNU Public
 */
class Pas_Twfy {


	/** The base url for api calls to twfy
	*
	*/
	const TWFYURL = 'http://www.theyworkforyou.com/api/';

	/** Set the type of response to retrieve
	*
	* @var string $format
	*/
	protected $_format = 'js';

	/** Set up the cache
	*
	* @var type
	*/
	protected $_cache;
	
	/** The api key
    *
    * @var type
    */
	protected $_apikey;

	/** Construct the object, sets the cache and need the api key for twfy
	 *
	 * @param string $key
	 */
	public function __construct(){
	$this->_apikey = Zend_Registry::get('config')->webservice->twfy->apikey;
	$frontendOptions = array(
            'lifetime' => 31556926,
            'automatic_serialization' => true
            );
	$backendOptions = array('cache_dir' => CACHE_PATH . '/twfy');
	$this->_cache = Zend_Cache::factory(
                'Output',
                'File',
                $frontendOptions,
                $backendOptions)
                ;
	}

	/** Perform a curl request based on url provided
	* @access public
	* @uses Zend_Cache
	* @uses Zend_Json_Decoder
	* @param string $method
	* @param array $params
	*/
	public function get($method, array $params) {
	$url = $this->createUrl($method,$params);
	if (!($this->_cache->test(md5($url)))) {
	$config = array(
        'adapter'   => 'Zend_Http_Client_Adapter_Curl',
        'curloptions' => array(
	CURLOPT_POST =>  true,
	CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
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
	$data = $this->_cache->load(md5($url));
	}
	return Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
	}

	/** Build a url string
	* @param string $method The method to use
	* @param array $params
	*/
	public function createUrl($method, array $params){
	if(is_array($params)){
	return self::TWFYURL . $method . '?' . http_build_query($params);
	} else {
	throw new Pas_Twfy_Exception('Parameters have to be an array',500);
	}
	}
}