<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostcodeToGeo
 *
 * @author danielpett
 */
class Pas_Service_Geo_PostCodeToGeo {

    protected $_cache;

    protected $_params = array();

    protected $_uri = 'http://mapit.mysociety.org/postcode/';

    protected $_validator;

    public function __construct() {
    $this->_cache = Zend_Registry::get('cache');
    $this->_validator = new Pas_Validate_ValidPostCode();
    }


    public function getData($postcode) {
    if($this->_validator->isValid($postcode)){
    $postcode = str_replace(' ', '', $postcode);
    } else {
        throw new Pas_Geo_Exception('Invalid postcode sent');
    }
    $key = md5($postcode);
    if (!($this->_cache->test($key))) {
    $response = $this->_get($postcode);
    $this->_cache->save($response);
    } else {
    $response = $this->_cache->load($key);
    }
    $geo = json_decode($response);
    return array('lat' => $geo->wgs84_lat, 'lon' => $geo->wgs84_lon);
    }

    protected function _get($postcode){
    $config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
        CURLOPT_POST =>  true,
        CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_LOW_SPEED_TIME => 1
	),
	);
    $client = new Zend_Http_Client($this->_uri . $postcode, $config);
    $response = $client->request();

    $code = $this->getStatus($response);
    if($code == true){
    return $response->getBody();
    } else {
            return NULL;
    }
    }

    private function getStatus($response) {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Exception('A valid appid parameter is required for this resource');
    		break;
    	case ($code == 404):
    		throw new Exception('The resource could not be found');
    		break;
    	case ($code == 406):
    		throw new Exception('You asked for an unknown representation');
    		break;
    	default;
    		return false;
    		break;
    }
    }

    public function validatePostcode($postCode){
        if($this->_validator->isValid($postCode)){
            return true;
        } else {
            return false;
        }
    }



}
