<?php
/** A service class for interacting with the Domesday map website
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 *  $domesday = new Pas_Service_Domesday_Place();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://URL name
 * @version 1
 * @category Pas
 * @package Pas_Service
 * @subpackage Domesday
 * @example /library/Pas/View/Helper/DomesdayNear.php
 * 
 */
class Pas_Service_Domesday_Place extends Zend_Rest_Client {

    /** The array of parameters to send
     * @access protected
     * @var type 
     */
    protected $_params = array();

    /** The uri of the service
     * @access protected
     * @var string
     */
    protected $_uri = 'http://domesdaymap.co.uk';

    /** The response type array
     * @access protected
     * @var array
     */
    protected $_responseTypes = array('xml', 'json', 'django');

    /** The default response type
     * @access protected
     * @var string
     */
    protected $_responseType = 'json';

    /** The methods available
     * @access protected
     * @var array
     */
    protected $_methods = array(
        'place', 'placesnear', 'manor',
    	'image', 'hundred', 'area',
    	'county' 
        );

    /** The place parameters to use
     * @access protected
     * @var array
     */
    protected $_placeNearParams = array(
	    'lat', 'lng', 'radius',
	    's', 'e', 'n',
	    'w','format');

    /** The path to the api
     * @access protected
     * @var string
     */
    protected $_apiPath = '/api/1.0/';

    /** The constructor
     * @access public
     */
    public function __construct(){
	$this->setUri($this->_uri);
	$client = self::getHttpClient();
	$client->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');
    }

    /** Set the parameters for searching
     * @access public
     * @param array $params
     * @return \Pas_Service_Domesday_Place
     */
    public function setParams(array $params) {
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

    /** Get the parameters
     * @access @access public
     * @return type
     */
    public function getParams(){
    	return $this->_params;
    }

    /** Set the response type to retrieve
     * @access protected
     * @param string $responseType
     * @return string
     * @throws Pas_Service_Domesday_Exception
     */
    public function setResponseType($responseType) {
        if (!in_array(strtolower($responseType), $this->_responseTypes)) {
            throw new Pas_Service_Domesday_Exception('Invalid Response Type');
        }
        $this->_responseType = strtolower($responseType);
        return $this->_responseType;
    }

    /** Get the response type
     * @access public
     * @return string
     */
    public function getResponseType() {
        return $this->_responseType;
    }

    /** Send the request
     * @access public
     * @param string $requestType
     * @param string $path
     * @return \Zend_Http_Response
     * @throws Pas_Service_Domesday_Exception
     */
    public function sendRequest($requestType, $path) {
        $requestType = ucfirst(strtolower($requestType));
        if ($requestType !== 'Post' && $requestType !== 'Get') {
            throw new Pas_Service_Domesday_Exception('Invalid request type: ' . $requestType);
        }
        try {
            $requestMethod = 'rest' . $requestType;
            $response = $this->{$requestMethod}($path, $this->getParams());
            return $this->formatResponse($response);
        } catch (Zend_Http_Client_Exception $e) {
            throw new Pas_Service_Domesday_Exception($e->getMessage());
        }
    }

    /** Set up the response rendering
     * @access public
     * @param string $response
     */
    public function formatResponse(Zend_Http_Response $response){
        if ('json' === $this->getResponseType()) {
            return json_decode($response->getBody());
        }  else {
            return new Zend_Rest_Client_Result($response->getBody());
        }
    }

    /** Retrieve data from the api
     * @access public
     * @param string $method
     * @param array $params
     */
    public function getData($method, array $params = array()) {
    	if(!in_array($method,$this->_methods)){
            throw new Pas_Service_Domesday_Exception('That is not a valid method');
    	}
    	foreach($params as $k => $v) {
            if(!in_array($k, $this->_placeNearParams)){
                unset($params['k']);
                }
	}
    	$this->setParams($params);
        $path = $this->_apiPath . $method;
        return $this->sendRequest('GET', $path);
    }
}