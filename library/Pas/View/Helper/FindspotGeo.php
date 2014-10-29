<?php
/** A view helper for creating findspot data when geo data is known
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @since September 28 2011
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Service_Geo_Geoplanet
 * @uses Pas_View_Helper_YahooGeoAdjacent
 * @uses Zend_Auth
 * @uses Pas_Service_Geo_Geoplanet
 * @uses Zend_Cache
 */
class Pas_View_Helper_FindspotGeo extends Zend_View_Helper_Abstract {

    /** The auth object
     * @access protected
     * @var object $_auth
    */
    protected $_auth = null;

    /** The auth object
     * @access protected
     * @var \Zend_Cache
    */
    protected $_cache = null;

    /** The config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config = null;

    /** The geoplanet class object
     * @access protected
     * @var Pas_Service_Geo_Geoplanet
     */
    protected $_geoplanet;

    /** The appid object
     * @access protected
     * @var string
     */
    protected $_appid = null;
    
    protected $_woeid;
    
    protected $_lat;
    
    protected $_lon;
    
    public function getWoeid() {
        return $this->_woeid;
    }

    public function getLat() {
        return $this->_lat;
    }

    public function getLon() {
        return $this->_lon;
    }

    public function setWoeid($woeid) {
        $this->_woeid = $woeid;
        return $this;
    }

    public function setLat($lat) {
        $this->_lat = $lat;
        return $this;
    }

    public function setLon($lon) {
        $this->_lon = $lon;
        return $this;
    }

    
    /** The constructor
     * @access public
     * @return void
     */
    public function __construct() {
        $this->_auth = Zend_Registry::get('auth');
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_appid = $this->_config->webservice->ydnkeys->consumerkey;
        $this->_geoplanet = new Pas_Service_Geo_Geoplanet($this->_appid);
    }
    
    /** Call the function to created findspot with geo data
     * @access public
     * @return \Pas_View_Helper_FindspotGeo
     */
    public function findspotGeo() {
        return $this;
    }

    
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        if ($this->getWoeid()) {
            $place = $this->_geoplanet->reverseGeocode($this->getLat(),$this->getLon());
            $placeData = $this->_geoplanet->getPlace($place['woeid']);
        } else {
            $placeData = $this->_geoplanet->getPlace($this->getWoeid());
        }
        return $this->buildHtml($placeData);
    }
    /** Function for determining whether elevation is -ve or +ve or =
     * @access public
     * @param int $elevation
     * @return string $string
     */
    public function metres($elevation) {
        switch ($elevation) {
            case ($elevation === 0):
                $string = 'sea level.';
                break;
            case ($elevation > 0):
                $string = $elevation . ' metres above sea level.';
                break;
            case ($elevation < 0):
                $string = $elevation . ' metres below sea level.';
                break;
        }

        return $string;
    }

    /** Build the HTML for rendering
     * @access public
     * @param array $data
     * @return string $html
     */
    public function buildHtml(array $data) {
        $html = '<h3 class="lead">Data from Yahoo! GeoPlanet</h3>';
        if ($data) {
            $html .= '<p>The spatially enriched data provided here was sourced from the excellent Places/Placemaker service';
            $html .= ' from Yahoo\'s geo team.<br />';
            $html .= 'Settlement type: ' . $data['placeTypeName'] . '<br/>';
            $html .= 'WOEID: <a href="http://woe.spum.org/id/' . $data['woeid'] . '">' . $data['woeid'] . '</a><br/>';
            if (array_key_exists('postal',$data)) {
                $html .= 'Postcode: ' . $data['postal'] . '<br/>';
            }
            if (array_key_exists('admin1',$data)) {
                $html .= 'Country: ' . $data['admin1'] . '<br/>';
            }
            $html .= '</p>';
        } else {
            $html .= '<p>No data returned</p>';
        }
        return $html;
    }
}