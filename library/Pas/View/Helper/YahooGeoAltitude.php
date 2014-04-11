<?php 
/** A view helper for getting geo altitude
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @license GNU
 * @since 30 September 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @uses Zend_Http_Client
 *
 */
class Pas_View_Helper_YahooGeoAltitude 
	extends Zend_View_Helper_Abstract {

	const URL = 'http://www.geomojo.org/cgi-bin/getaltitude.cgi?';
	const YQL = 'http://query.yahooapis.com/v1/public/yql?format=json&q=';
	const FORMAT = '&format=json';
	const UNITSPOS = ' metres above sea level.';
	const UNITSNEG = ' metres below sea level.';
	const STRING = 'Elevation: ';
	
	/** Get the altitude from woeid
	 * Not sure of this is needed now
	 * @param $woeid
	 */
	public function getAltitudeWoeid($woeid) {
	if(is_null($woeid)) {
	throw new Exception('No unique WOEID was passed to this helper');
	} else {
	$args = 'woeid=' . $woeid;
	$url = URL . $args . FORMAT;
	$json = $this->get($url);
	$altitude = $json->altitude;
	return STRING . $altitude . UNITSPOS; 
	}
	}

	/** Get the altitude from lat and lon
	 * @param float $lat
	 * @param float $lon
	 */
	public function getAltitudeLatLon($lat,$lon) {
	if($lat == NULL && $lon == NULL) {
	throw new Exception('Your latitude/ longitude pair is incorrectly formed');
	} else {
	$query = 'SELECT * FROM flickr.places WHERE lat =\'' . $lat . '\' AND lon =\'' . $lon.'\'';
	$url = self::YQL . urlencode($query);
	$json = $this->get($url);
	$altitude = $json->altitude;
	if($altitude > 0) {
	return STRING . $altitude . UNITSPOS;
	} else {
	return STRING . $altitude . UNITSNEG;
	} 
	}
	}
	
	/** Use curl to get the data
	 * @param string $url
	 * @return obhect $json
	 */
	public function get($url) {
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(CURLOPT_POST =>  true,
						   CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
						   CURLOPT_FOLLOWLOCATION => true,
						   CURLOPT_RETURNTRANSFER => true,
						   CURLOPT_LOW_SPEED_TIME => 1
						   ),
	);
	$client = new Zend_Http_Client($url, $config);
	$response = $client->request();
	$data = $response->getBody();
	$json = json_decode($data);
	return $json;
	}
	
	
	




}