<?php
/** A class for getting elevation of a latlon point against the google api
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @package Pas_Service
 * @subpackage Geo
 * @category Pas
 */
class Pas_Service_Geo_Elevation{

    const ELEVATIONURI = 'http://maps.googleapis.com/maps/api/elevation/json';
    

    /** Get the coordinates from an address string
     * @param float $lat
     * @param float $lon
     * @access public
     */
    public function _getElevationApiCall($lat, $lon) {
        $client = new Zend_Http_Client();
        $client->setUri(self::ELEVATIONURI);
        $client->setParameterGet('locations', (string)$lon . ',' . (string)$lat)
               ->setParameterGet('sensor', 'false');
        $result = $client->request('GET');
        $response = Zend_Json_Decoder::decode($result->getBody(),
                    Zend_Json::TYPE_OBJECT);
        return $response;
    }
	
    /** Get the coordinates of an address
 	 * @param float $lat
     * @param float $lon
     * @access public
     */
    public function getElevation($lat, $lon)  {
        $response = $this->_getElevationApiCall((string)$lat, (string)$lon);
     	if(isset($response->results[0]->elevation)){
            return $response->results[0]->elevation;
        } else {
			return null;
		}
    }

}
