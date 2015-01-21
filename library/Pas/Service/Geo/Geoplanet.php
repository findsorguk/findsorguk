<?php

/** A class for parsing geo data from Yahoo geoplanet
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $geoPlanet = new Pas_Service_Geo_Geoplanet($appid);
 * $yahoo = $place->reverseGeoCode($lat,$lon);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Service
 * @subpackage Geo
 * @version  1
 * @since September 27 2011
 * @uses          Pas_Yql_Oauth
 * @uses          Pas_Geo_Parser
 * @example /app/models/Coroners.php
 */
class Pas_Service_Geo_GeoPlanet
{

    /** The yahoo api endpoint
     * @var string
     */
    const API_URI = 'http://where.yahooapis.com/v1/';

    /** The geonames url
     * @todo this might be worth changing to google elevation api
     * @var string
     */
    const ELEVATION_URI = 'http://ws.geonames.org/astergdemJSON?';

    /** The language to use
     * @var string
     */
    const LANG = 'en-US';

    /** The YQL endpoint - authorised
     * @var string
     */
    const YQL_URI = 'http://query.yahooapis.com/v1/public/yql?format=json&_maxage=7200&q=';

    /** The community table string
     * @var string
     */
    const YQL_TABLES = '&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

    /** The context mime type
     * @var string
     */
    const CONTENT = 'text/plain';

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The oauth object
     * @access protected
     * @var \Pas_Yql_Oauth
     */
    protected $_oauth;

    /** The app id for Yahoo
     * @access protected
     * @var string
     */
    protected $_appID;

    /** The access token
     * @access protected
     * @var string
     */
    protected $_accessToken;

    /** The access secret
     * @access protected
     * @var string
     */
    protected $_accessSecret;

    /** The expiry date
     * @access protected
     * @var string
     */
    protected $_accessExpiry;

    /** The oauth handle
     * @access protected
     * @var string
     */
    protected $_handle;

    /** The parser class to use
     * @access protected
     * @var \Pas_Service_Geo_Parser
     */
    protected $_parser;

    /** Set up the constructor
     * @access public
     * @param string $appid The Yahoo application ID
     */
    public function __construct($appid)
    {
        $this->_appID = $appid;
        $this->_cache = Zend_Registry::get('cache');
        $this->_oauth = new Pas_Yql_Oauth();
        $tokens = new OauthTokens();
        $where = array();
        $where[] = $tokens->getAdapter()->quoteInto('service = ?', 'yahooAccess');
        $validToken = $tokens->fetchRow($where);
        if ($validToken) {
            $this->_accessToken = unserialize($validToken->accessToken);
            $this->_accessSecret = unserialize($validToken->tokenSecret);
            $this->_accessExpiry = $validToken->expires;
            $this->_handle = unserialize($validToken->sessionHandle);
        }
        $this->_parser = new Pas_Service_Geo_Parser();
    }

    /** Get the elevation of a point
     * @access public
     * @param integer $woeid The where on earth ID
     * @param double $lat
     * @param double $lon
     * @return boolean| integer
     */
    public function getElevation($woeid, $lat, $lon)
    {
        if (!is_null($woeid) || $woeid != '') {
            $key = 'elevation' . $woeid;
            if (!$place = $this->_cache->load($key)) {
                $point = $this->getPlace($woeid);
                $lat = $point['latitude'];
                $lon = $point['longitude'];
                $yql = 'select * from json where url="' . self::ELEVATION_URI
                    . 'lat=' . $lat . '&lng=' . $lon . '";';
                $place = $this->_oauth->execute(
                    $yql, $this->_accessToken, $this->_accessSecret,
                    $this->_accessExpiry, $this->_handle);
                $this->_cache->save($place);
            } else {
                $place = $this->_cache->load($key);
            }
            if (sizeof($place) > 0) {
                $place = $this->_parser->parseElevation($place);
                return $place;
            } else {
                return false;
            }
        } else if (!is_null($lat) && !is_null($lon)) {
            $key2 = 'elevation' . md5($lat . $lon);
            if (!$place = $this->_cache->load($key2)) {
                $yql = 'select * from json where url="' . self::ELEVATION_URI
                    . 'lat=' . $lat . '&lng=' . $lon . '";';
                $place = $this->_oauth->execute($yql, $this->_accessToken,
                    $this->_accessSecret, $this->_accessExpiry, $this->_handle);
                $this->_cache->save($place);
            } else {
                $place = $this->_cache->load($key2);
            }
            if (sizeof($place) > 0) {
                $place = $this->_parser->parseElevation($place);
                return $place;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /** Get a place from the WOEID
     * @access public
     * @param integer $woeid
     * @return boolean|array
     */
    public function getPlace($woeid)
    {
        if (!is_null($woeid)) {
            $key = 'geoplaceID' . $woeid;
            if (!($this->_cache->test($key))) {
                $yql = 'select * from geo.places where woeid = ' . $woeid;
                $place = $this->_oauth->execute($yql, $this->_accessToken,
                    $this->_accessSecret, $this->_accessExpiry, $this->_handle);
                $this->_cache->save($place);
            } else {
                $place = $this->_cache->load($key);
            }
            return $this->_parser->parsePlace($place);
        } else {
            return false;
        }
    }

    /** Get a place from a text string
     * @param string $string
     * @return boolean|array
     */
    public function getPlaceFromText($string)
    {
        if (strlen($string) > 3) {
            $yql = 'select * from geo.places where text="' . $string . '";';
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlace($place);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get a list of places in a text string
     * @param string $text
     * @return boolean|array
     */
    public function getPlaces($text)
    {
        if (strlen($text) > 3) {
            $yql = 'select * from geo.placemaker where documentContent = "'
                . strip_tags($text) . '" and documentType="'
                . self::CONTENT . '" AND appid = "' . $this->_appID . '";';
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlaces($place);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get all the places adjacent to a woeid
     * @param integer $woeid
     * @return boolean|array
     */
    public function getAdjacentToWoeid($woeid)
    {
        if (strlen($woeid) > 0) {
            $yql = 'select * from geo.places.neighbors where neighbor_woeid = ' . $woeid;
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlaceFromList($place->query->results);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get the parent of a woeid
     * @param integer $woeid
     * @return boolean|array
     */
    public function getParentOfWoeid($woeid)
    {
        if (strlen($woeid) > 0) {
            $yql = 'select * from geo.places.parent where child_woeid = ' . $woeid;
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlace($place);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get the siblings
     * @param integer $woeid
     * @return boolean|array
     */
    public function getSiblingsOfWoeid($woeid)
    {
        if (strlen($woeid) > 0) {
            $yql = 'select * from geo.places.siblings where sibling_woeid = ' . $woeid;
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlaceFromList($place->query->results);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get the ancestors of a Woeid
     * @param $woeid
     * @return boolean|array
     */
    public function getAncestorsOfWoeid($woeid)
    {
        if (strlen($woeid) > 0) {
            $yql = 'select * from geo.places.ancestors where descendant_woeid = ' . $woeid;
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlaceFromList($place->query->results);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get the woeid that the current one belongs to (parent!)
     * @access public
     * @param int $woeid
     * @return boolean|array
     */
    public function getWoeidBelongsTo($woeid)
    {
        if (strlen($woeid) > 0) {
            $yql = 'select * from geo.places.belongtos where member_woeid = ' . $woeid;
            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);
            if (sizeof($place) > 0) {
                $placeData = $this->_parser->parsePlaceFromList($place->query->results);
                return $placeData;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /** Get the distance between two woeids
     * @access public
     * @param int $place1
     * @param int $place2
     */
    public function getDistance($place1, $place2)
    {
        $yql = 'select * from geo.distance where place1="' . $place1
            . '" and place2="' . $place2 . '";';
        $place = $this->_oauth->execute($yql, $this->_accessToken,
            $this->_accessSecret, $this->_accessExpiry, $this->_handle);
        return $place;
    }

    /** Call all data in one lump!
     * @access public
     * @author Chris Heilmann originally for the YQL statements
     * @param integer $woeid
     * @return boolean|array
     */
    public function getThePlanet($woeid)
    {
        if (strlen($woeid) > 0) {
            $yql = 'select * from query.multi where queries = "' .
                'select * from geo.places where woeid = ' . $woeid . ';' .
                'select * from geo.places.ancestors where descendant_woeid = ' . $woeid . ';' .
                'select * from geo.places.belongtos where member_woeid = ' . $woeid . ';' .
                'select * from geo.places.children where parent_woeid = ' . $woeid . ';' .
                'select * from geo.places.neighbors where neighbor_woeid = ' . $woeid . ';' .
                'select * from geo.places.parent where child_woeid = ' . $woeid . ';' .
                'select * from geo.places.siblings where sibling_woeid = ' . $woeid . '"';

            $place = $this->_oauth->execute($yql, $this->_accessToken,
                $this->_accessSecret, $this->_accessExpiry, $this->_handle);

            $placeData = array();
            $placeData['place'] = $this->_parser->parseSinglePlace($place->query->results->results['0']->place);
            $placeData['ancestors'] = $this->_parser->parsePlaceFromList($place->query->results->results['1']);
            $placeData['belongsTo'] = $this->_parser->parsePlaceFromList($place->query->results->results['2']);
            $placeData['children'] = $this->_parser->parsePlaceFromList($place->query->results->results['3']);
            $placeData['neighbours'] = $this->_parser->parsePlaceFromList($place->query->results->results['4']);
            $placeData['parent'] = $this->_parser->parseSinglePlace($place->query->results->results['5']->place);
            $placeData['siblings'] = $this->_parser->parsePlaceFromList($place->query->results->results['6']);
            return $placeData;
        } else {
            return false;
        }
    }

    /** Reverse geocode for woeid and other data
     * @param float $lat
     * @param float $lon
     * @return array|boolean
     */
    public function reverseGeoCode($lat, $lon)
    {
        if (!is_null($lat) && !is_null($lon)) {
            $key = 'geocode' . md5($lat . $lon);
            if (!$place = $this->_cache->load($key)) {
                $yql = 'SELECT * FROM geo.placefinder where text="' . $lat . ',' . $lon . '" and gflags="R"';
                //    $yql = 'SELECT * FROM xml WHERE url="http://where.yahooapis.com/geocode?location=' . $lat . '+' .  $lon
                //    . '&gflags=R&appid=' . $this->_appID . '"';

                $place = $this->_oauth->execute($yql, $this->_accessToken,
                    $this->_accessSecret, $this->_accessExpiry, $this->_handle);
                $this->_cache->save($place);
            } else {
                $place = $this->_cache->load($key);
            }
            if (sizeof($place) > 0) {
                $place = $this->_parser->parseGeocoded($place);
                return $place;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}