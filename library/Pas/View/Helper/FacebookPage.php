<?php
/**
 * A view helper for determining which findspot partial to display to the user
 *
 * An example of use
 * <code>
 * <?php
 * echo $this->facebookPage();
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @license http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Http_Client_Adapter_Curl
 * @uses Zend_Http_Client
 * @todo this class can be cut substantially for the user object to come from just one call
 */
class Pas_View_Helper_FacebookPage extends Zend_View_Helper_Abstract
{
    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The page ID to query
     * @access protected
     * @var int
     */
    protected $_pageid;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The url to query
     * @access protected
     * @var string
     */
    protected $_url;

    /** The header type to not try and parse
     * @access public
     * @var string
     */
    protected $_header = 'text/html;charset=UTF-8';

    /** Get the cache object
     * @access public
     * @return type
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('rulercache');
        return $this->_cache;
    }

    /** Get the page ID
     * @access public
     * @return int
     */
    public function getPageid() {
        $this->_pageid = $this->getConfig()->webservice->facebook->pageid;
        return $this->_pageid;
    }

    /** Get config
     * @access public
     * @return object
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the facebook graph page ID to query
     * @access public
     * @return string
     */
    public function getUrl() {
        $this->_url = 'https://graph.facebook.com/' . $this->getPageid();
        return $this->_url;
    }

    /** The port to access
     * @access protected
     * @var type
     */
    protected $_port = 80;

    /** Get the port
     * @access public
     * @return int
     */
    public function getPort() {
        return $this->_port;
    }

    /** Set a different port
     * @access public
     * @param int $port
     * @return \Pas_View_Helper_FacebookPage
     */
    public function setPort( $port) {
        $this->_port = $port;
        return $this;
    }

    /** Set up and use Curl
     * @access public
     * @return object The curl client
     */
    public function getCurlConfig() {
        $config = array(
            'adapter'   => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_POST =>  false,
                CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_PORT => $this->getPort(),
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_LOW_SPEED_TIME => 1,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT => 1,
                ),
            );
         $client = new Zend_Http_Client($this->getUrl(), $config);
         return $client;
    }
    /** Get the data
     * @access public
     * @return type
     */
    public function getData() {
        $data = '';
        $response = $this->getCurlConfig()->request();
        $code = $this->getStatus($response);
        $header = $response->getHeaders();
        if ($code == 200 && $header != $this->_header) {
            $data = $this->getDecode($response);
        }
        return $data;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FacebookPage
     */
    public function facebookPage() {
        return $this;
    }

    /** Parse the facebook result
     * @access public
     * @return type
     */
    public function parseFacebook() {
        if (!($this->getCache()->test('facebookCounts'))) {
        $data = $this->getData();
        $this->getCache()->save($data);
        } else {
        $data = $this->getCache->load('facebookCounts');
        }
        return $this->buildHtml($data);
    }

    /** Build the html
     * @access public
     * @param object $data
     * @return string
     */
    public function buildHtml(object $data){
        $html = '';
        $html .= '<li class="purple"><p>Join our ';
        $html .= $data->likes;
        $html .= ' friends on ';
        $html .= '<a href="';
        $html .= $data->link;
        $html .= '">facebook</a></p></li>';
        return $html;
    }

    /** Decode the response
     * @access public
     * @param object $response
     * @return type
     */
    private function getDecode( object $response) {
        $data = $response->getBody();
        $json = json_decode($data);
        return $json;
    }


    /** Get the status of the response
     * @access public
     * @param object $response
     * @return int
     */
    private function getStatus( object $response) {
        $code = $response->getStatus();
        switch ($code) {
            case ($code == 200):
                $http = 200;
                break;
            case ($code == 400):
                $http = 400;
                break;
            case ($code == 404):
                $http = 404;
                break;
            case ($code == 406):
                $http = 406;
                break;
            default;
                $http = 999;
                break;
        }
        return $http;
    }

    /** The to String function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->parseFacebook();
    }
}
