<?php 


	class Pas_Placemaker {

	protected $_suffix;
	
	protected $_woeid;
	
	protected $_config;
	
	protected $_autoDisambiguate = false;
	
	protected $_postal;
	/** 
     * Yahoo Application ID from YDN 
     * @link https://developer.apps.yahoo.com/wsregapp/ 
     */  
	protected $_appid;
	
	protected $_documentType = 'text/plain';
	/** 
	* Yahoo placemaker endpoint url
	*/ 
	const YAHOOAPI = 'http://wherein.yahooapis.com/v1/document';

	protected $_format = 'xml';
		
	public function __construct($suffix){
	$this->_config = Zend_Registry::get('config');
	$this->_appid = $this->_config->webservice->ydnkeys->placemakerkey;	
	$this->_suffix = $suffix;
	}
		
    private function getStatus($response) {
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
	
	public function curl($text,$args) {
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
	CURLOPT_POST =>  true,
	CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_PORT => 80,
	CURLOPT_HEADER => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_LOW_SPEED_TIME => 1,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_CONNECTTIMEOUT => 1,
	CURLOPT_POSTFIELDS => $args
	),
	);
	$client = new Zend_Http_Client( self::YAHOOAPI , $config );
	$client->setMethod(Zend_Http_Client::POST);
	$response = $client->request();
	if ($response->isSuccessful()){
	
	$code = $this->getStatus($response);
	$header = $response->getHeaders();
	if($code == true && $header != 'text/html;charset=UTF-8'){
	return $response;	
	} else {
	return NULL;
	}
	} else {
		return NULL;
	}
	}
		
	public function getSingle($text) {
	$args =array( 
	'appid' => $this->_appid,
	'documentContent' => urlencode($text . ' ' . $this->_suffix), 
    'documentType' => 'text/plain', 
    'outputType' => 'xml', 
	); 
	
	$response = $this->curl($text,$args);		
	// Now parse the response using PHP SimpleXML
	$xml = simplexml_load_string($response);
	Zend_Debug::dump($xml);
	$place = new Place();
	if (isset($xml->results->place->name)) {
	$place->name = $xml->results->place->name;
	}
	if (isset($xml->results->place->centroid->longitude)) {
	$place->longitude = $xml->results->place->centroid->longitude;
	}
	if (isset($xml->results->place->centroid->latitude)) {
	$place->latitude = $xml->results->place->centroid->latitude;
	}
	if (isset($xml->results->place->woeid)) {
	$place->woeid = $xml->results->place->woeid;
	}
	if (isset($xml->results->place->placeTypeName)) {
	$place->type = $xml->results->place->placeTypeName;
	} 
	if (isset($xml->results->place->postal)) {
	$place->postal = $xml->results->place->postal;
	}
	return $place;
	}
		
		// Extracts all the locations from a document.
		// Retrieves an array of Placemaker objects.
	public function getMultiplePlaces($text) {
	$places = array();
	$args =array( 
	'documentContent' => $text . ' ' . $this->suffixUsed, 
    'documentType' => 'text/plain', 
    'outputType' => 'xml', 
    'autoDisambiguate' => 'false',  
	'appid' => $this->_appid); 
	
	$response = $this->curl($text,$args);			
	// Now parse the response using PHP SimpleXML
	$xml = simplexml_load_string($response);
	foreach($xml->document->placeDetails as $pd) {
	$place = new Placemaker();
	$place->woeid = $pd->place->woeId;
	$place->name = $pd->place->name;
	$place->longitude = $pd->place->centroid->longitude;
	$place->latitude = $pd->place->centroid->latitude;
	$places[] = $place;
	}
	return $places;
	}
	}
