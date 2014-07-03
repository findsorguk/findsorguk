<?php
/** Base class for interfacing with the theyworkforyou api
 *
 * An example of code use:
 *
 * <code>
 *
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @see theyworkforyou.com/api
 * @since 1/2/2012
 * @uses Zend_Cache
 * @uses Zend_Http_Client
 * @category Pas
 * @package Twfy
 * @version 1
 * @license GNU Public
 * @example path description
 */
class Pas_Twfy {

    /** The base url for api calls to twfy
    *
    */
    const TWFYURL = 'http://www.theyworkforyou.com/api/';

    /** Set the type of response to retrieve
    * @access protected
    * @var string $format
    */
    protected $_format = 'js';

    /** Set up the cache
    * @access protected
    * @var \Zend_Cache
    */
    protected $_cache;

    /** The api key
     * @access protected
     * @var string
     */
    protected $_apikey;

    /** Construct the object, sets the cache and need the api key for twfy
     * @access public
     * @param string $key
     */
    public function __construct(){
        $this->_apikey = Zend_Registry::get('config')->webservice->twfy->apikey;
        $this->_cache = Zend_Registry::get('cache');
    }

    /** Perform a curl request based on url provided
     * @access public
     * @param string $method
     * @param array $params
     * @return type
     */
    public function get($method, array $params) {
        $url = $this->createUrl($method,$params);
        if (!($this->_cache->test(md5($url)))) {
            $config = array(

                'adapter'   => 'Zend_Http_Client_Adapter_Curl',
                'curloptions' => array(
                    CURLOPT_POST =>  true,
                    CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_LOW_SPEED_TIME => 1,
                    ),
                );
            $client = new Zend_Http_Client($url, $config);
            $response = $client->request();
            $data = $response->getBody();
            $this->_cache->save($data);
        } else {
            $data = $this->_cache->load(md5($url));
        }
        return Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
    }

    /** Build a url string
     * @access public
     * @param string $method
     * @param array $params
     * @return string
     * @throws Pas_Twfy_Exception
     */
    public function createUrl($method, array $params){
        if(is_array($params)){
            return self::TWFYURL . $method . '?' . http_build_query($params);
        } else {
            throw new Pas_Twfy_Exception('Parameters have to be an array',500);
        }
    }
}