<?php
/** A class for geocoding against the google api
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $geocoder = new Pas_Service_Geo_Coder($apikey);
 * $geocoder->getCoordinates($address);
 * ?>
 * </code>
 * 
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @package Pas_Service
 * @subpackage Geo
 * @category Pas
 * @see https://developers.google.com/maps/documentation/geocoding/
 * @todo extend for use of extra components and parameters (not needed at present, so 
 * class is minimal.
 * @example /library/Pas/Controller/Action/Helper/GeoCoder.php
 */
class Pas_Service_Geo_Coder {

    /** Geocoder uri
     * @var unknown_type
     */
    const GEOCODEURI = 'https://maps.googleapis.com/maps/api/geocode/json';
    

    /** Get the coordinates from an address string
     * @access public
     * @param string $address
     */
    public function _getGeocodedLatitudeAndLongitude($address) {
        $client = new Zend_Http_Client();
        $client->setUri(self::GEOCODEURI);
        $client->setParameterGet('address', $address)
               ->setParameterGet('sensor', 'false');
        $result = $client->request('GET');
        $response = Zend_Json_Decoder::decode($result->getBody(),
                    Zend_Json::TYPE_OBJECT);
        return $response;
    }
	
    /** Get the coordinates of an address
     * @access public
     * @param string $address
     */
    public function getCoordinates($address)  {
        $response = $this->_getGeocodedLatitudeAndLongitude($address);
        if(isset($response->results[0]->geometry->location)){
             return array(
                'lat' => $response->results[0]->geometry->location->lat,
                'lon' => $response->results[0]->geometry->location->lng
            );
        } else {
            return null;
        }
    }
}