<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** Base class for accessing the Edina Unlock Geo api
 * Caching of calls is handled in this class
 *
 * @category Pas
 * @package Pas_Geo
 * @subpackage Edina
 * @since 3/2/12
 * @license GNU Public
 * @version 1
 * @copyright Daniel Pett, British Museum
 * @author Daniel Pett
 *
 */
class Pas_Geo_Edina {

    /** The base URL for the unlock service
     *
     */
    const UNLOCK_URI = 'http://unlock.edina.ac.uk/ws/';

    /** Set up the cache
    * @var type
    */
    protected $_cache;


    /** The url to query via curl
     *
     * @var type
     */
    protected $_url;

    /** The array of available formats for service
     *
     * @var type
     */
    protected $_formats = array('json', 'kml', 'xml', 'txt', 'georss');

    /** The default type of response
     *
     * @var type
     */
    protected $_format = 'json';

    /** The available gazetteers to use
     *
     * @var type
     */
    protected $_gazetteers = array('geonames', 'os', 'naturalearth', 'unlock');

    /** The default gazetteer to use
     *
     * @var type
     */
    protected $_gazetteer = 'unlock';

    /** Construct cache, get the user agent for curl
     *
     */
    public function __construct(){

    $frontendOptions = array(
        'lifetime' => 31556926, //Sets the monster cache for 1 year
        'automatic_serialization' => true
        );

    $backendOptions = array(
        'cache_dir' => CACHE_PATH . '/edina'
        );

    $this->_cache = Zend_Cache::factory(
            'Output',
            'File',
            $frontendOptions,
            $backendOptions)
            ;

    }

    /** Perform a curl request based on url provided
    * @access public
    * @uses Zend_Cache
    * @uses Zend_Json_Decoder
    * @param string $method
    * @param array $params
    */
    public function get($method, array $params) {

    $this->_url = $this->_createUrl($method, $params);

    if (!($this->_cache->test(md5($this->_url)))) {
    $config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
    CURLOPT_POST =>  true,
    CURLOPT_USERAGENT =>  $this->_getUserAgent(),
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_LOW_SPEED_TIME => 1,
    ),
    );

    $client = new Zend_Http_Client($this->_url, $config);
    $response = $client->request();

    $data = $response->getBody();
    $this->_cache->save($data);
    } else {
    $data = $this->_cache->load(md5($this->_url));
    }
    if($this->getFormat() === 'json'){
        return $this->_decoder($data);
    } else {
        return $data;
    }
    }

    /** Decode the json response as an object
     * @access protected
     * @param string $data
     * @return object
     */
    protected function _decoder($data){
        return Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
    }

    /** Build a url string
    * @access protected
    * @param string $method The method to use
    * @param array $params
    */
    protected function _createUrl($method, array $params){
    if(!$this->_checkMethods($method)){
        throw new Pas_Geo_Edina_Exception('That method is not in the api');
    }
    if(is_array($params)){
        $defaults = array(
            'gazetteer' => $this->_gazetteer,
            'format' => $this->_format
                );
        $params = array_merge($params, $defaults);
    return self::UNLOCK_URI . $method . http_build_query($params, null, '&');
    } else {
        throw new Pas_Geo_Edina_Exception('Parameters have to be an array',500);
    }
    }

    /** Get the user Agent for sending curl response
     * @access protected
     * @return string
     */
    protected function _getUserAgent(){
    $useragent = new Zend_Http_UserAgent();
    return $useragent->getUserAgent();

    }

    /** Set a different gazetteer to default of unlock
     * @access public
     * @param string $gazetteer
     * @return string
     * @throws Pas_Geo_Edina_Exception
     */
    public function setGazetteer($gazetteer){
        $gazetteer = strtolower($gazetteer);
        if(!in_array($gazetteer, $this->_gazetteers)){
            throw new Pas_Geo_Edina_Exception('That gazetteer is not valid');
        } else {
            return $this->_gazetteer = $gazetteer;
        }
    }

    /** Set a different format to get response other than json
     * @access public
     * @param string $format
     * @return type
     * @throws Pas_Geo_Edina_Exception
     */
    public function setFormat($format) {
        $format = strtolower($format);
        if(!in_array($format, $this->_formats)){
            throw new Pas_Geo_Edina_Exception('That format is not valid');
        } else {
            return $this->_format = $format;
        }
    }

    /** Get the format called
     * @access public
     * @return string
     */
    public function getFormat(){
        return $this->_format;
    }

    /** Get the url queried
     * @access public
     * @return string
     */
    public function getUrl(){
        return $this->_url;
    }

    /** Available API methods to call
     * @access protected
     * @var array
     */
    protected $_methods = array(
        'closestMatchSearch?',
        'supportedFeatureTypes?',
        'featureLookup?',
        'footprintLookup?',
        'nameAndFeatureSearch?',
        'nameSearch?',
        'postCodeSearch?',
        'spatialNameSearch?',
        'uniqueNameSearch?',
        'spatialFeatureSearch?'
        );

    /** An access method check
     *
     * @param type $method
     * @return boolean
     */
    protected function _checkMethods($method){
        if(in_array($method, $this->_methods)){
            return true;
        } else {
            return false;
    }
}
}

