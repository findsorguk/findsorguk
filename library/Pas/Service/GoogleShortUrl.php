<?php
/**
 * A view helper for shortening and expanding links with goo.gl
 * 
 * @category   Pas
 * @package    Service
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 * @author Daniel Pett
 * @since September 28 2011
 */
class Pas_Service_GoogleShortUrl {
 
	const GOOGLE = 'https://www.googleapis.com/urlshortener/v1/url';
	
	const INVALIDURL = 'Your entry is not a valid URL.';
	
	const INVALIDSHORTURL = 'That is not a valid google shortened url';
	
	const GOOGLEURL = 'goo.gl';
	
	protected $_api;
	
	/** Constructor
	 * @acess public
	 * @param string $key The Google api key
	 * @return void
	 */
	public function __construct( $key ) {
	$this->_api = self::GOOGLE . '?key=' . $key;
	}

	/** Function to shorten a given url
	 * @access public
	 * @param string $url URL to shorten
	 * @return object $reponse Shortened URL
	 */
	public function shorten( $url ) {
	$url = $this->checkUrl( $url );
	$response = $this->send($url,true);
	return $response;
    }     

    /** Expand a url from goo.gl's api
     * @access public
     * @param string $url URL to expand
     * @return object $response
     */
    public function expand($url ) {
	$url = $this->checkShortUrl( $url );
	$response = $this->send($url,false);
	return $response;
    }
	
    /** Get analytics for a URL
     * @access public
     * @param string $shortUrl
     * @return object $response
     */
    public function analytics($shortUrl){
	$url = $this->checkShortUrl( $shortUrl );
	$client = new Zend_Http_Client();
	$client->setUri($this->_api);
	$client->setMethod(Zend_Http_Client::GET);
	$client->setParameterGet('shortUrl', $shortUrl);
	$client->setParameterGet('projection', 'FULL');
	$response = $client->request();
	if($response->isSuccessful()){
	return $this->getDecode($response);
	} else {
		return false;
	}
    }
    
    /** Decode the response from JSON
     * @access public
     * @param string $response
     * @return object $json
     */
    private function getDecode($response){
    $data = $response->getBody();
	$json = json_decode($data);
	return $json;	
    }
    
    /** Check the request status
     * @access private
     * @param object $response
     */
	private function getStatus($response) {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Exception('Bad request made');
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
	
	/** Check the short URL is valid as a goo.gl one
	 * @access private
	 * @param string $url
	 * @return string $url
	 * @throws Exception
	 */
	private function checkShortUrl($url){
	$shorturl = parse_url($url);
		if($shorturl['host'] === self::GOOGLEURL){
			return $url;
		} else {
	throw new Exception(self::INVALIDSHORTURL);		
	}	
	}
	/** Send a url for shortening 
	 * @access private
	 * @param string $url
	 * @param boolean $short
	 */
	private function send($url, $short = true) {
	if($short){
	$options = array(
	CURLOPT_URL => $this->_api, 
	CURLOPT_POST => true,             
	CURLOPT_HEADER => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_RETURNTRANSFER =>  1, 
	);	
	$config = array(
	'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	'curloptions' => $options
	);
	$client = new Zend_Http_Client( $this->_api, $config );
	$client->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/json');
	$client->setMethod(Zend_Http_Client::POST);
	$client->setRawData(json_encode(array("longUrl"=>$url)));
	} else {
	$options = array(
	CURLOPT_URL => $this->_api . '&shortUrl=' . $url,
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_RETURNTRANSFER =>  1, 
	);
	$config = array(
	'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	'curloptions' => $options
	);
	$client = new Zend_Http_Client( $this->_api, $config );
	}
	$response = $client->request();
	if($response->isSuccessful()) {
	$code = $this->getStatus($response);
	$header = $response->getHeaders();
	if($code == true && $header != 'text/html;charset=UTF-8'){
		return $this->getDecode($response);	
	} else {
		return NULL;
	}
	} else {
		return NULL;
	}
	}

}
