<?php
/**
 * A view helper for determining which findspot partial to display to the user
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @todo this class can be cut substantially for the user object to come from just one call
 */
class Pas_View_Helper_FacebookPage {
	
	protected $_cache;
	protected $_pageid;
	protected $_config;
	protected $_url;
	
	public function __construct(){
		$this->_cache = Zend_Registry::get('rulercache');
		$this->_config = Zend_Registry::get('config');
		$this->_pageid = $this->_config->webservice->facebook->pageid;
		$this->_url = 'https://graph.facebook.com/' . $this->_pageid;
	}

	/** Initiate the call via curl
	 * 
	 * @param string $url
	 * @param integer $port
	 */
	public function curl($url,$port = 80) {
	$config = array(
	'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	'curloptions' => array(
	CURLOPT_POST =>  false,
	CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_PORT => $port,
	CURLOPT_HEADER => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_LOW_SPEED_TIME => 1,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_CONNECTTIMEOUT => 1,
//	CURLOPT_DNS_USE_GLOBAL_CACHE => false,
//	CURLOPT_DNS_CACHE_TIMEOUT => 2,
//	CURLOPT_PROXY => 'http://81.29.72.72',
//	CURLOPT_PROXYPORT => 80
	),
	);
//	Zend_Debug::dump($config);
	$request = $url;
	$client = new Zend_Http_Client($request, $config);
	$response = $client->request();
	$code = $this->getStatus($response);
	$header = $response->getHeaders();
	if($code == true && $header != 'text/html;charset=UTF-8'){
	$data = $this->getDecode($response);
	return $data;	
	} else {
	return NULL;
	}
	}
	
	/** Retrieve a facebook page details user the id set in the config ini /**
	 * Caches the results. 
	 */
	public function facebookPage() {
	if (!($this->_cache->test('facebookCounts'))) {
	$data = $this->curl($this->_url);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('facebookCounts');
	}
	return $this->buildHtml($data);
	}
	
	/** Build the html response from the data returned from the facebook call
	 * 
	 * @param array $data
	 */
	public function buildHtml($data){
		//Zend_Debug::dump($data);
	$html = '';
	$html .= '<li class="purple"><p>Join our ';
	$html .= $data->likes;
	$html .= ' friends on ';
	$html .= '<a href="' . $data->link . '">facebook</a></p></li>';
	return $html;
	}

	/** Decode the response from the curl call
 	* 
 	* @param object $response
 	*/
	private function getDecode($response) {
	$data = $response->getBody();
	$json = json_decode($data);
	return $json;	
	}
    
	/** Check the status of the response
	* 
	* @param object $response
	*/
	private function getStatus($response){
	$code = $response->getStatus();
	switch($code) {
	case ($code == 200):
		return true;
		break;
		case ($code == 400):
		throw new Exception('A valid appid parameter is required for this resource');
		break;
    	case ($code == 404):
		throw new Exception('The resource could not be found');
		break;
    	case ($code == 406):
		throw new Exception('You asked for an unknown representation');
		break;
    	default;
		return false;
		break;	
	}
	}
}

