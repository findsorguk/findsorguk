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
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @package Pas_Service
 * @subpackage Geo
 * @category Pas
 * @see https://developers.google.com/maps/documentation/geocoding/
 * @todo extend for use of extra components and parameters (not needed at present, so
 * class is minimal.
 * @example /library/Pas/Controller/Action/Helper/GeoCoder.php
 */
class Pas_Service_Geo_Coder
{

    /** Geocoder uri
     * @var string
     */
    private const GEOCODEURI = 'https://maps.googleapis.com/maps/api/geocode/json';

    /** Google Geocoder API Key
     * @var string
     */
    private const APIKEY = null; // No API Key Set


    /** Get the coordinates from an address string via the Google geocoding API
     * @param $address
     * @return mixed
     * @throws Zend_Http_Client_Exception
     * @throws Zend_Json_Exception
     * @link https://developers.google.com/maps/documentation/geocoding
     */
    private function getGeocodedLatitudeAndLongitudeFromGoogleGeoAPI($address)
    {
        $client = new Zend_Http_Client();
        $client->setUri(self::GEOCODEURI);
        $client->setParameterGet('address', $address)
            ->setParameterGet('sensor', 'false')
            ->setParameterGet('key', self::APIKEY);
        $result = $client->request('GET');
        return Zend_Json_Decoder::decode($result->getBody(), Zend_Json::TYPE_OBJECT);
    }

    /** Get the coordinates of an address
     * @access public
     * @param string $address
     */
    public function getCoordinates($address)
    {
        if (!empty(self::APIKEY)) {
            $response = $this->getGeocodedLatitudeAndLongitudeFromGoogleGeoAPI($address);
            if (isset($response->results[0]->geometry->location)) {
                return array(
                    'lat' => $response->results[0]->geometry->location->lat,
                    'lon' => $response->results[0]->geometry->location->lng
                );
            } else {
                return null;
            }
        } else {
            return null; //Geo data cannot be found from Address without Google API Key
        }
    }
}