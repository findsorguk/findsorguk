<?php 
/** A class to query the Yahoo placemaker service
 * @category Pas
 * @package Pas_Service_Geo
 * @subpackage Placemaker
 * @author Daniel Pett
 * @version 1
 * @todo Perhaps add in YQL ouath version
 * @todo add caching
 * @todo add multiple function
 * @copyright Daniel Pett
 * @since September 30 2011
 * @license GNU
 */

class Pas_Service_Geo_Placemaker {

	
	protected $_woeid;
	
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

	protected $_format = 'json';
	
	protected $_suffix = 'en-GB'; 

	/** Construct the class
	 * 
	 * @param string $appid The Yahoo appid
	 */
	public function __construct($appid, $suffix = NULL ){
	$this->_appid = $appid;
	if(isset($suffix)){
	$this->_suffix = $suffix;
	}
	}

	
	//Decide thd status response and throw response
    private function getStatus($response) {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Pas_Geo_Exception('A valid appid parameter is required for this resource');
    		break;
		case ($code == 403):
    		throw new Pas_Geo_Exception('You have exceeded your allowed calls to Yahoo api');
    		break;
    	case ($code == 404):
    		throw new Pas_Geo_Exception('The resource could not be found');
    		break;
    	case ($code == 405):
    		throw new Pas_Geo_Exception('Input is invalid');
    		break;
    	case ($code == 406):
    		throw new Pas_Geo_Exception('You asked for an unknown representation');
    		break;
    	default;
    		return false;
    		break;	
    }
	}
	
	/** Post the Data to Yahoo for response
	 * 
	 * @param array $args
	 */
	public function postData( $args) {
	$client = new Zend_Http_Client( self::YAHOOAPI 
	);
	$client->setParameterPost($args);
	$response = $client->request(Zend_Http_Client::POST);
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
	
	/** Create a place from a json response
	 * 
	 * @param string $json
	 */
	protected function createPlace($json){
	$geo = array();
	$geo['name'] = $json->placeDetails->place->name;
	$geo['type'] = $json->placeDetails->place->type;
	$geo['woeid'] = $json->placeDetails->place->woeId;
	$geo['confidence'] = $json->placeDetails->confidence;
	$geo['lat'] = $json->placeDetails->place->centroid->latitude;
	$geo['lon'] = $json->placeDetails->place->centroid->longitude;
	$geo['bbox'] = array();
	$geo['bbox']['swlat'] = $json->extents->southWest->latitude;
	$geo['bbox']['swlon'] = $json->extents->southWest->longitude;
	$geo['bbox']['nelon'] = $json->extents->northEast->latitude;
	$geo['bbox']['nelat'] = $json->extents->northEast->longitude;
	return (object)$geo;
	}
	
	/** Get data for single place based off text
	 * 
	 * @param string $text String of text to geocode
	 */
	public function getSingle($text) {
	$args =array( 
	'appid' => $this->_appid,
	'documentContent' => urlencode($text . ' ' . $this->_suffix), 
    'documentType' => $this->_documentType, 
    'outputType' => $this->_format, 
	); 
	$response = $this->postData($args);
	$json = json_decode($response->getBody());
	return $this->createPlace($json->document);
	}
		
//	// Extracts all the locations from a document.
//	// Retrieves an array of Placemaker objects.
//	public function getMultiplePlaces($text) {
//	$args =array( 
//	'documentContent' => $text . ' ' . $this->_suffix, 
//    'documentType' => $this->_documentType, 
//    'outputType' => $this->_format, 
//    'autoDisambiguate' => 'false',  
//	'appid' => $this->_appid); 
//	
//	$response = $this->postData($args);			
//	
//	return $places;
//	}
	}
