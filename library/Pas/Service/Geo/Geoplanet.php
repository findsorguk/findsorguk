<?php
/**
* A class for parsing geo data from Yahoo geoplanet
* @category   Pas
* @package    service
* @subpackage Geo
* @copyright  Copyright (c) 2011
* @license    GNU General Public License
* @version	  1
* @author     Daniel Pett
* @since 	  September 27 2011
* @uses 	  Pas_Yql_Oauth
* @uses		  Pas_Geo_Parser
* @todo		  Sort out class to have a non oauth YQL fallback
*
*/
class Pas_Service_Geo_Geoplanet {

	/** The yahoo api endpoint
	 *
	 * @var string
	 */
 	const API_URI = 'http://where.yahooapis.com/v1/';

 	/** The geonames url
 	 * @todo this might be worth changing to google elevation api
 	 * @var string
 	 */
 	const ELEVATION_URI = 'http://ws.geonames.org/astergdemJSON?';

 	/** The language to use
 	 *
 	 * @var string
 	 */
 	const LANG    = 'en-US';

 	/** The YQL endpoint - authorised
 	 *
 	 * @var string
 	 */
 	const YQL_URI = 'http://query.yahooapis.com/v1/public/yql?format=json&_maxage=7200&q=';

 	/** The community table string
 	 *
 	 * @var string
 	 */
 	const YQL_TABLES = '&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

 	/** The context mime type
 	 *
 	 * @var string
 	 */
 	const CONTENT = 'text/plain';

 	protected $_cache, $_oauth, $_appID, $_accessToken, $_accessSecret;

 	protected $_accessExpiry, $_handle, $_parser;

	/** Set up the constructor
	 * @param string $appid The Yahoo application ID
	 */
 	public function __construct( $appid ) {
	$this->_appID = $appid;
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
	$tokens = new OauthTokens();
	$where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess');
	$validToken = $tokens->fetchRow($where);
        if($validToken){
	$this->_accessToken= unserialize($validToken->accessToken);
	$this->_accessSecret = unserialize($validToken->tokenSecret);
	$this->_accessExpiry = $validToken->expires;
	$this->_handle = unserialize($validToken->sessionHandle);
        }
	$this->_parser = new Pas_Service_Geo_Parser();
	}

	/** Get the eleveation of a point
	 *
	 * @param integer $woeid The where on earth ID
	 * @param double $lat
	 * @param double $lon
	 */
	public function getElevation($woeid, $lat, $lon) {
	if(!is_null($woeid) || $woeid != ''){
	$key = 'elevation'.$woeid;
	if (!$place = $this->_cache->load($key)) {
	$point = $this->getPlace($woeid);
	$lat = $point['latitude'];
	$lon = $point['longitude'];
	$yql = 'select * from json where url="' . self::ELEVATION_URI . 'lat=' . $lat  . '&lng=' . $lon. '";';
	$place = $this->_oauth->execute($yql, $this->_accessToken, $this->_accessSecret,$this->_accessExpiry, $this->_handle);
	$this->_cache->save($place);
	} else {
		$place = $this->_cache->load($key);
	}
	if(sizeof($place) > 0) {
		$place = $this->_parser->parseElevation($place);
    return $place;
     } else {
    return false;
    }
    } else if(!is_null($lat) && !is_null($lon)) {
    $key2 = 'elevation'.md5($lat.$lon);
    if (!$place = $this->_cache->load($key2)) {
    $yql = 'select * from json where url="' . self::ELEVATION_URI . 'lat=' . $lat  . '&lng=' . $lon. '";';
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    $this->_cache->save($place);
	} else {
	$place = $this->_cache->load($key2);
	}
	if(sizeof($place) > 0) {
    $place =  $this->_parser->parseElevation($place);
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
     */
    public function getPlace( $woeid = NULL )  {
    if(!is_null($woeid)){
    $key = 'geoplaceID' . $woeid;
    if (!($this->_cache->test($key))) {
    $yql = 'select * from geo.places where woeid = ' . $woeid;
    $place = $this->_oauth->execute($yql, $this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    $this->_cache->save($place);
	} else {
	$place = $this->_cache->load($key);
	}

    return  $this->_parser->parsePlace( $place );

    } else {
    return false;
    }
    }

    /** Get a place from a text string
     * @param string $string
     */
    public function getPlaceFromText( $string )  {
    if(strlen($string) > 3) {
    $yql = 'select * from geo.places where text="' . $string  . '";';
	$place = $this->_oauth->execute($yql, $this->_accessToken,
    $this->_accessSecret, $this->_accessExpiry, $this->_handle);
    if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlace($place);
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
     */
    public function getPlaces($text)  {
    if(strlen($text) > 3) {
    $yql = 'select * from geo.placemaker where documentContent = "' . strip_tags($text) . '" and documentType="'
    . self::CONTENT . '" AND appid = "' . $this->_appID . '";';
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlaces($place);
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
     */
    public function getAdjacentToWoeid($woeid) {
    if(strlen($woeid) > 0){
   	$yql = 'select * from geo.places.neighbors where neighbor_woeid = ' . $woeid;
   	$place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlaceFromList($place->query->results);
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
     */
    public function getParentOfWoeid( $woeid ) {
	if(strlen($woeid) > 0){
    $yql = 'select * from geo.places.parent where child_woeid = ' . $woeid;
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlace($place);
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
     */
    public function getSiblingsOfWoeid($woeid) {
    if(strlen($woeid) > 0){
    $yql = 'select * from geo.places.siblings where sibling_woeid = ' . $woeid;
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlaceFromList($place->query->results);
    return $placeData;
    } else {
    return false;
    }
   	} else {
   	return false;
   	}
    }

    /** Get the ancestors of a Woeid
	*
    * @param $woeid
    */
    public function getAncestorsOfWoeid( $woeid )  {
    if(strlen($woeid) > 0){
    $yql = 'select * from geo.places.ancestors where descendant_woeid = ' . $woeid;
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
     if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlaceFromList($place->query->results);
    return $placeData;
    } else {
    return false;
    }
	} else {
	return false;
	}
    }

    /** Get the woeid that the current one belongs to (parent!)
     * @param int $woeid
     */
    public function getWoeidBelongsTo( $woeid ) {
    if(strlen($woeid) > 0){
    $yql =  'select * from geo.places.belongtos where member_woeid = ' . $woeid;
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    if(sizeof($place) > 0) {
    $placeData =  $this->_parser->parsePlaceFromList($place->query->results);
    return $placeData;
    } else {
    return false;
    }
    } else {
    	return false;
    }
    }

    /** Get the distance between two woeid
     *
     * @param int $place1
     * @param int $place2
     */
    public function getDistance($place1, $place2) {
    $yql = 'select * from geo.distance where place1="' . $place1 . '" and place2="' . $place2 . '";';
    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    return $place;
    }

    /** Call all data in one lump!
 	* @author Chris Heilmann originally for the YQL statements
 	*
 	*/
    public function getThePlanet( $woeid ) {
    if(strlen($woeid) > 0){
    $yql= 'select * from query.multi where queries = "'.
    'select * from geo.places where woeid = ' . $woeid . ';'.
    'select * from geo.places.ancestors where descendant_woeid = ' . $woeid . ';' .
    'select * from geo.places.belongtos where member_woeid = ' . $woeid . ';' .
    'select * from geo.places.children where parent_woeid = ' . $woeid . ';' .
    'select * from geo.places.neighbors where neighbor_woeid = ' . $woeid . ';' .
    'select * from geo.places.parent where child_woeid = ' . $woeid . ';' .
    'select * from geo.places.siblings where sibling_woeid = ' . $woeid . '"';

    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);

    $placeData = array();
    $placeData['place']      = $this->_parser->parseSinglePlace($place->query->results->results['0']->place);
    $placeData['ancestors']  = $this->_parser->parsePlaceFromList($place->query->results->results['1']);
    $placeData['belongsTo']  = $this->_parser->parsePlaceFromList($place->query->results->results['2']);
    $placeData['children']   = $this->_parser->parsePlaceFromList($place->query->results->results['3']);
    $placeData['neighbours'] = $this->_parser->parsePlaceFromList($place->query->results->results['4']);
    $placeData['parent'] 	 = $this->_parser->parseSinglePlace($place->query->results->results['5']->place);
    $placeData['siblings'] 	 = $this->_parser->parsePlaceFromList($place->query->results->results['6']);
	return $placeData;
    } else {
	return false;
    }
  	}

  	/** Reverse geocode for woeid and other data
  	 * @param float $lat
  	 * @param float $lon
  	 */
	public function reverseGeoCode($lat,$lon) {
    if(!is_null($lat) && !is_null($lon)){
    $key = 'geocode' . md5($lat . $lon);
    if (!$place = $this->_cache->load($key)) {
    $yql = 'SELECT * FROM geo.placefinder where text="' . $lat . ',' . $lon . '" and gflags="R"';
//    $yql = 'SELECT * FROM xml WHERE url="http://where.yahooapis.com/geocode?location=' . $lat . '+' .  $lon
//    . '&gflags=R&appid=' . $this->_appID . '"';

    $place = $this->_oauth->execute($yql,$this->_accessToken,
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
    $this->_cache->save($place);
	} else {
	$place = $this->_cache->load($key);
	}
    if(sizeof($place) > 0) {
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
