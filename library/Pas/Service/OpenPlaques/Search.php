<?php

/** A service class for searching the openplaques api
 * An example of code use:
 *
 * <code>
 * <?php
 * $op = new Pas_Service_OpenPlaques_Search();
 * $params = array(
 * 'format' => 'json' ,
 * 'phrase' => $phrase)
 * );
 * $resp = $op->getData('search', $params );
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @package Pas_Service
 * @subpackage OpenPlaques
 * @license
 * @example /app/modules/experiments/views/scripts/middleeast/person.phtml
 */
class Pas_Service_OpenPlaques_Search extends Zend_Rest_Client
{

    /** The array of parameters to send
     * @access protected
     * @var array
     */
    protected $_params = array();

    /** The uri of the site
     * @access protected
     * @var string
     */
    protected $_uri = 'http://openplaques.org';

    /** The possible response types the service uses
     * @access protected
     * @var array
     */
    protected $_responseTypes = array('xml', 'json');

    /** The methods available for use
     * @access protected
     * @var array
     */
    protected $_methods = array('search?', 'plaques?');

    /** The api path
     * @access protected
     * @var string
     */
    protected $_apiPath = '/';

    /** Construct the class
     * @access public
     */
    public function __construct()
    {
        $this->setUri($this->_uri);
        $client = self::getHttpClient();
        $client->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');
    }

    /** Set the params to query
     * @access @access public
     * @param array $params
     * @return \Pas_Service_OpenPlaques_Search
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $value) {
            switch (strtolower($key)) {
                case 'format':
                    $this->_params['format'] = $this->setResponseType($value);
                    break;
                default:
                    $this->_params[$key] = $value;
                    break;
            }
        }
        return $this;
    }

    /** Get the params for searching
     * @access public
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /** Set the response type
     * @access public
     * @param string $responseType
     * @return string
     * @throws Pas_Service_OpenPlaques_Exception
     */
    public function setResponseType($responseType)
    {
        if (!in_array(strtolower($responseType), $this->_responseTypes)) {
            throw new Pas_Service_OpenPlaques_Exception('Invalid Response Type');
        }
        $this->_responseType = strtolower($responseType);
        return $this->_responseType;
    }

    /** get the response type
     * @access public
     * @return string
     */
    public function getResponseType()
    {
        return $this->_responseType;
    }

    /** Sen the request
     * @access public
     * @param string $requestType
     * @param string $path
     * @return \Zend_Http_Response
     * @throws Pas_Service_OpenPlaques_Exception
     */
    public function sendRequest($requestType, $path)
    {
        $requestTypeTransform = ucfirst(strtolower($requestType));
        if ($requestTypeTransform !== 'Post' && $requestTypeTransform !== 'Get') {
            throw new Pas_Service_OpenPlaques_Exception('Invalid request type: ' . $requestType);
        }
        try {
            $requestMethod = 'rest' . $requestTypeTransform;
            $response = $this->{$requestMethod}($path, $this->getParams());
            return $this->formatResponse($response);
        } catch (Zend_Http_Client_Exception $e) {
            throw new Pas_Service_OpenPlaques_Exception($e->getMessage());
        }
    }

    /** Set up the response rendering
     * @access public
     * @param Zend_Http_Response $response
     */
    public function formatResponse(Zend_Http_Response $response)
    {
        if ('json' === $this->getResponseType()) {
            return json_decode($response->getBody());
        } else {
            return new Zend_Rest_Client_Result($response->getBody());
        }
    }

    /** Retrieve data from the api
     * @access public
     * @param string $method
     * @param array $params
     */
    public function getData($method, array $params = array())
    {
        if (!in_array($method . '?', $this->_methods)) {
            throw new Pas_Service_OpenPlaques_Exception('That is not a valid method');
        }
        $this->setParams($params);
        $path = $this->_apiPath . $method;
        return $this->sendRequest('GET', $path);
    }
}