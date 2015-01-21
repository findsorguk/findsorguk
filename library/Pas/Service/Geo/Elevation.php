<?php

/** A class for getting elevation of a latlon point against the google api
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $elevation = new Pas_Service_GeoCoder($key);
 * $elevation->getElevation($lat,$lon);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @package Pas_Service
 * @subpackage Geo
 * @category Pas
 * @example /app/models/Findspots.php
 */
class Pas_Service_Geo_Elevation
{

    /** The api uri
     *
     */
    const ELEVATIONURI = 'http://maps.googleapis.com/maps/api/elevation/json';


    /** Get the coordinates from an address string
     * @access public
     * @param float $lat
     * @param float $lon
     * @access public
     */
    public function _getElevationApiCall($lat, $lon)
    {
        $client = new Zend_Http_Client();
        $client->setUri(self::ELEVATIONURI);
        $client->setParameterGet('locations', (string)$lon . ',' . (string)$lat)->setParameterGet('sensor', 'false');
        $result = $client->request('GET');
        $response = Zend_Json_Decoder::decode($result->getBody(), Zend_Json::TYPE_OBJECT);
        return $response;
    }

    /** Get the coordinates of an address
     * @access public
     * @param float $lat
     * @param float $lon
     * @access public
     */
    public function getElevation($lat, $lon)
    {
        $response = $this->_getElevationApiCall((string)$lat, (string)$lon);
        if (isset($response->results[0]->elevation)) {
            return $response->results[0]->elevation;
        } else {
            return null;
        }
    }
}
