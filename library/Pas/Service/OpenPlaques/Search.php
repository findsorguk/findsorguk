<?php

class Pas_Service_OpenPlaques_Search
	extends Zend_Rest_Client {

	protected $_params = array();

	protected $_uri = 'http://openplaques.org';

	protected $_responseTypes = array('xml', 'json');

    protected $_methods = array('search?' , 'plaques?' );

	protected $_apiPath = '/';

	public function __construct(){
	$this->setUri($this->_uri);
	$client = self::getHttpClient();
	$client->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');
	}

	public function setParams($params)
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

    public function getParams()
    {
    	return $this->_params;
    }

    public function setResponseType($responseType)
    {
        if (!in_array(strtolower($responseType), $this->_responseTypes)) {
            throw new Pas_Service_OpenPlaques_Exception('Invalid Response Type');
        }
        $this->_responseType = strtolower($responseType);
        return $this->_responseType;
    }

    public function getResponseType()
    {
        return $this->_responseType;
    }

    public function sendRequest($requestType, $path)
    {
        $requestType = ucfirst(strtolower($requestType));
        if ($requestType !== 'Post' && $requestType !== 'Get') {
            throw new Pas_Service_OpenPlaques_Exception('Invalid request type: ' . $requestType);
        }

        try {
            $requestMethod = 'rest' . $requestType;
            $response = $this->{$requestMethod}($path, $this->getParams());
            return $this->formatResponse($response);
        } catch (Zend_Http_Client_Exception $e) {
            throw new Pas_Service_OpenPlaques_Exception($e->getMessage());
        }
    }

    /** Set up the response rendering
     *
     * @param string $response
     */
    public function formatResponse(Zend_Http_Response $response)
    {
   
        if ('json' === $this->getResponseType()) {
            return json_decode($response->getBody());
        }  else {
            return new Zend_Rest_Client_Result($response->getBody());
        }
    }

    /** Retrieve data from the api
     *
     * @param string $method
     * @param array $params
     */
    public function getData($method, array $params = array())
    {
    	if(!in_array($method . '?',$this->_methods)){
    		throw new Pas_Service_OpenPlaques_Exception('That is not a valid method');
    	}
    	$this->setParams($params);
    	$path = $this->_apiPath . $method;
        return $this->sendRequest('GET', $path);
    }

}