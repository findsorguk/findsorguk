<?php
/**
* A class for executing YQL queries via oauth
*
* @category   Pas
* @package    Yql
* @subpackage Oauth
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0

*/
class Pas_Yql_Oauth {
	
	const YQL = 'http://query.yahooapis.com/v1/yql';
	
	/** The data table URL for community use
	 * 
	 * @var string
	 */
	const DATATABLES_URL  = 'store://datatables.org/alltableswithkeys';
		
	protected $_consumerKey;
	
	protected $_consumerSecret;
	
	protected $_config;

	protected $_now;
	
	
	/** This probably needs changing for construction methods.
	 * @todo remove need for calling config
	 * @todo pass variables to the constructor
	 */
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_consumerKey = $this->_config->webservice->ydnkeys->consumerKey; 
	$this->_consumerSecret = $this->_config->webservice->ydnkeys->consumerSecret;
	$this->_now = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	/** Execute the YQL call
	 * 
	 * @param string $consumerKey
	 * @param string $consumerSecret
	 * @param string $q The YQL string
	 * @param string $access_token
	 * @param string $access_token_secret
	 * @param string $access_token_expiry
	 * @param string $oauthhandle
	 */
	public function execute( $q, $access_token, $access_token_secret, 
	$access_token_expiry, $oauthhandle){
	
	$expired = $this->hasExpired($access_token_expiry);
	if($expired === false){
	$response = $this->callYQL($q,$access_token,$access_token_secret);
	return $response;	
	}	else {
	$newToken = $this->refresh_access_token($access_token,$access_token_secret,$oauthhandle);
	$newToken = (object)$newToken;
	$response = $this->callYQL($q,urldecode($newToken->oauth_token),urldecode($newToken->oauth_token_secret));
	return $response;		
	}
	}
    
	/** Has the token expired?
	 * 
	 * @param string $access_token_expiry
	 */
	private function hasExpired($access_token_expiry) {
	$now = new Zend_Date(NULL,'yyyy-MM-dd HH:mm:ss');

  	$tokenDate = new Zend_Date($access_token_expiry,'YYYY-MM-dd HH:mm:ss');
  	$difference = $tokenDate->isLater($now); 
 	if(($now > $tokenDate)){
 	return true;
 	} else {
 	return false;
 	}
	}	
  
  
	/** Decode the response for json object
	 * 
	 * @param object $response
	 */
	private function getDecode($response) {
	$data = $response->getBody();
	return $data;	
	}
	
	/** Determine response status
	* @see http://developer.yahoo.com/search/errors.html
	* @param object $response
	*/
	private function getStatus($response) {
	$code = $response->getStatus();
	switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Pas_Yql_Exception('Bad request. The parameters passed to the service did not match as expected. 
    		The Message should tell you what was missing or incorrect.');
    		break;
    	case ($code == 403):
    		throw new Pas_Yql_Exception('Forbidden. You do not have permission to access this resource, 
    		or are over your rate limit.');
    		break;
    	case ($code == 404):
    		throw new Pas_Yql_Exception('The resource could not be found');
    		break;
    	case ($code == 406):
    		throw new Pas_Yql_Exception('You asked for an unknown representation');
    		break;
    	case ($code == 503):
    		throw new Pas_Yql_Exception('Service unavailable. An internal problem prevented 
    		us from returning data to you.');
    		break;
    	default;
    		return false;
    		break;	
    	}
	}
	
	/** Call YQL using Oauth
	 * 
	 * @param string $q
	 * @param string $access_token
	 * @param string $access_token_secret
	 * @param boolean $passOAuthInHeader
	 * @param string $method
	 */
	private function callYQL($q,$access_token, $access_token_secret, $passOAuthInHeader = true,
		$method = 'GET') {
  	$url = self::YQL;
	$params['q'] = (string)$q;
	$params['format'] = 'json';
	$params['env'] = self::DATATABLES_URL;
	$params['oauth_version'] = '1.0';
	$params['oauth_nonce'] = $this->generate_nonce();
	$params['oauth_timestamp'] = time();
	$params['oauth_consumer_key'] = $this->_consumerKey;
	$params['oauth_token'] = $access_token;
	$params['oauth_signature_method'] = 'HMAC-SHA1';
	$params['oauth_signature'] =
	$this->oauth_compute_hmac_sig(
	$method, $url, $params,
	$this->_consumerSecret, $access_token_secret);

	// Pass OAuth credentials in a separate header or in the query string
	if ($passOAuthInHeader) {
	$query_parameter_string = $this->oauth_http_build_query($params, true);
	$header = $this->build_oauth_header($params, "yahooapis.com");
	$headers[] = $header;
	} else {
	$query_parameter_string = $this->oauth_http_build_query($params);
	}
	$request_url = $url . ($query_parameter_string ? ('?' . $query_parameter_string) : '' );
	$response = $this->curl($request_url, 80, $headers);
	return $response;	
	}
    
	/** Generate a nonce
	* 
	*/
	private static function generate_nonce() {
    $mt = microtime();
	$rand = mt_rand();
	return md5($mt . $rand); 
	}
   
	/** Create an expiry time
  	 * 
  	 */
	private function expires() {
	$date = new Zend_Date();
	$expires = $date->add('1', Zend_Date::HOUR);
	$kickmeout = $expires->toString('yyyy-MM-dd HH:mm:ss');
	return $kickmeout;
	}
    
    /** Build an oauth http query
     * 
     * @param array $params
     * @param boolean $excludeOauthParams
     */
    private function oauth_http_build_query($params, $excludeOauthParams = false) {
	$query_string = '';
	if (! empty($params)) {
    // rfc3986 encode both keys and values
    $keys = OAuthUtil::urlencode_rfc3986(array_keys($params));
    $values = OAuthUtil::urlencode_rfc3986(array_values($params));
    $params = array_combine($keys, $values);
    // Parameters are sorted by name, using lexicographical byte value ordering.
    // http://oauth.net/core/1.0/#rfc.section.9.1.1
    uksort($params, 'strcmp');
    // Turn params array into an array of "key=value" strings
    $kvpairs = array();
    foreach ($params as $k => $v) {
	if ($excludeOauthParams && substr($k, 0, 5) == 'oauth') {
    continue;
    }
	if (is_array($v)) {
    // If two or more parameters share the same name,
	// they are sorted by their value. OAuth Spec: 9.1.1 (1)
	natsort($v);
	foreach ($v as $value_for_same_key) {
		array_push($kvpairs, ($k . '=' . $value_for_same_key));
	}
	} else {
		// For each parameter, the name is separated from the corresponding
	// value by an '=' character (ASCII code 61). OAuth Spec: 9.1.1 (2)
	array_push($kvpairs, ($k . '=' . $v));
	}
    }
    // Each name-value pair is separated by an '&' character, ASCII code 38.
	// OAuth Spec: 9.1.1 (2)
	$query_string = implode('&', $kvpairs);
	}
	return $query_string;
	}

	/**
	 * Parse a query string into an array.
	 * @param string $query_string an OAuth query parameter string
	 * @return array an array of query parameters
	 * @link http://oauth.net/core/1.0/#rfc.section.9.1.1
	 */
	private function oauth_parse_str($query_string) {
  	$query_array = array();
	if (isset($query_string)) {
    // Separate single string into an array of "key=value" strings
    $kvpairs = explode('&', $query_string);
    // Separate each "key=value" string into an array[key] = value
    foreach ($kvpairs as $pair) {
    list($k, $v) = explode('=', $pair, 2);
	// Handle the case where multiple values map to the same key
    // by pulling those values into an array themselves
	if (isset($query_array[$k])) {
	// If the existing value is a scalar, turn it into an array
	if (is_scalar($query_array[$k])) {
		$query_array[$k] = array($query_array[$k]);
	}
	array_push($query_array[$k], $v);
	} else {
	$query_array[$k] = $v;
	}
    }
	}
	return $query_array;
	}

	/**
	 * Build an OAuth header for API calls
	 * @param array $params an array of query parameters
	 * @return string encoded for insertion into HTTP header of API call
	 */
	private function build_oauth_header($params, $realm='') {
	  $header = 'Authorization: OAuth realm="' . $realm . '"';
	  foreach ($params as $k => $v) {
	    if (substr($k, 0, 5) == 'oauth') {
	      $header .= ',' . OAuthUtil::urlencode_rfc3986($k) . '="' . OAuthUtil::urlencode_rfc3986($v) . '"';
	    }
	  }
	  return $header;
	}

	/**
	 * Create an OAuth PLAINTEXT signature (an ampersand is all that separates the secrets
	 * @param string $consumer_secret
	 * @param string $token_secret
	 */
	private function oauth_compute_plaintext_sig($consumer_secret, $token_secret)
	{
	  return ($consumer_secret . '&' . $token_secret);
	}

	/**
	 * Compute an OAuth HMAC-SHA1 signature
	 * @param string $http_method GET, POST, etc.
	 * @param string $url
	 * @param array $params an array of query parameters for the request
	 * @param string $consumer_secret
	 * @param string $token_secret
	 * @return string a base64_encoded hmac-sha1 signature
	 * @see http://oauth.net/core/1.0/#rfc.section.A.5.1
	 */
	private function oauth_compute_hmac_sig($http_method, $url, $params, 
		$consumer_secret, $token_secret) {
  	$base_string = $this->signature_base_string($http_method, $url, $params);
  	$signature_key = OAuthUtil::urlencode_rfc3986($consumer_secret) . '&' . OAuthUtil::urlencode_rfc3986($token_secret);
  	return base64_encode(hash_hmac('sha1', $base_string, $signature_key, true));
	}

	/**
 	* Normalise the url format scheme://host/path
 	* @param string $url
 	* @return string the url in the form of scheme://host/path
 	*/
	private function normalize_url($url) {
	$parts = parse_url($url);
	$scheme = $parts['scheme'];
	$host = $parts['host'];
	$path = $parts['path'];
	return "$scheme://$host$path";
	}

	/**
	 * Returns the normalized signature base string of this request
	 * @param string $http_method
	 * @param string $url
	 * @param array $params
	 * The base string is defined as the method, the url and the
	 * parameters (normalized), each urlencoded and the concated with &.
	 * @see http://oauth.net/core/1.0/#rfc.section.A.5.1
 	*/
	private function signature_base_string($http_method, $url, $params) {
 	//Parse URL - see http://php.net/manual/en/function.parse-str.php
  	$query_str = parse_url($url, PHP_URL_QUERY);
  	if ($query_str) {
	$parsed_query = $this->oauth_parse_str($query_str);
    // merge params from the url with params array from caller
    $params = array_merge($params, $parsed_query);
  	}

  	// Strip out oauth_signature from params array if isset
  	if (isset($params['oauth_signature'])) {
    unset($params['oauth_signature']);
	}

	// Create a double encoded param signature base string
  	$base_string =  OAuthUtil::urlencode_rfc3986(strtoupper($http_method)) . '&' .
                    OAuthUtil::urlencode_rfc3986($this->normalize_url($url)) . '&' .
					OAuthUtil::urlencode_rfc3986($this->oauth_http_build_query($params));
	return $base_string;
	}

	/**
	 * Encode input per RFC 3986
	 * @param string|array $raw_input
	 * @return string|array properly rfc3986 encoded raw_input
	 * If an array is passed in, rfc3896 encode all elements of the array.
	 * @link http://oauth.net/core/1.0/#encoding_parameters
	 */
	private function rfc3986_decode($raw_input) {
	return rawurldecode($raw_input);
	}

	/** Get the URL via CURL 
	 * The important part for oauth is the $headers param
	 * @param string $url
	 * @param integer $port
	 * @param string $headers
	 */
	private function curl($url, $port, $headers) {
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(CURLOPT_POST 			=>  false,
						   CURLOPT_USERAGENT 		=>  $_SERVER["HTTP_USER_AGENT"],
						   CURLOPT_FOLLOWLOCATION 	=> true,
						   CURLOPT_PORT				=> $port,
						   CURLOPT_HEADER			=> false,
						   CURLOPT_RETURNTRANSFER 	=> true,
						   CURLOPT_LOW_SPEED_TIME 	=> 1,
						   CURLOPT_SSL_VERIFYHOST 	=> false,
                     	   CURLOPT_SSL_VERIFYPEER 	=> false,
                     	   CURLOPT_CONNECTTIMEOUT 	=> 1,
						   ),
	);
	$request = $url;
	
	$client = new Zend_Http_Client($request, $config);
	$client->setHeaders($headers);
	$response = $client->request();
	$code = $this->getStatus($response);
	$header = $response->getHeaders();

	if($code == true && $header != 'text/html;charset=UTF-8'){
	$data = $this->getDecode($response);
	return $data;	
	} else {
	return NULL;
	}
	}
	
	/** Create a token
	 * 
	 * @param array $data
	 */
	private function createToken($data) {
	if($data){
	$data = (object)$data;
	$tokens = new OauthTokens();
	$tokenRow = $tokens->createRow();	
	$tokenRow->service = 'yahooAccess';
	$tokenRow->accessToken = serialize(urldecode($data->oauth_token));
	$tokenRow->tokenSecret = serialize($data->oauth_token_secret);
	$tokenRow->guid = serialize($data->xoauth_yahoo_guid);
	$tokenRow->sessionHandle = serialize($data->oauth_session_handle);
	$tokenRow->created = $this->_now;
	$tokenRow->expires = $this->expires();
	$tokenRow->save();
	$tokenData = array('accessToken' => $data->oauth_token,'secret' => $data->oauth_token_secret);
	return $tokenData;
	} else {
		return false;
	}
	}

	/** Delete all the expired tokens
	 * 
	 */
 	private function cleanUp() {
 	$tokens = new OauthTokens();
	$where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
 	$where[] = $tokens->getAdapter()->quoteInto('expires <= ?', $this->_now);
 	$delete = $tokens->delete($where);
 	}
 
 	/** Refresh the access token with yahoo's service if expired 
 	 * You need to create your access tokens elsewhere
 	 * 
 	 * @param string $old_access_token
 	 * @param string $old_token_secret
 	 * @param string $oauth_session_handle
 	 * @param boolean $useHmacSha1Sig
 	 * @param boolean $passOAuthInHeader
 	 */
	private function refresh_access_token( $old_access_token, $old_token_secret, 
	$oauth_session_handle,  $useHmacSha1Sig = true, $passOAuthInHeader = true) {
	$url = 'https://api.login.yahoo.com/oauth/v2/get_token';
	$params['oauth_version'] = '1.0';
	$params['oauth_nonce'] = $this->generate_nonce();
	$params['oauth_timestamp'] = time();
	$params['oauth_consumer_key'] = $this->_consumerKey;
	$params['oauth_token'] = $old_access_token;
	$params['oauth_session_handle'] = $oauth_session_handle;
 	if ($useHmacSha1Sig) {
	$params['oauth_signature_method'] = 'HMAC-SHA1';
    $params['oauth_signature'] =
    $this->oauth_compute_hmac_sig('GET', $url, $params, $this->_consumerSecret, $old_token_secret);
	} else {
    $params['oauth_signature_method'] = 'PLAINTEXT';
    $params['oauth_signature'] =
	$this->oauth_compute_plaintext_sig($this->_consumerSecret, $old_token_secret);
	}
	if ($passOAuthInHeader) {
    $query_parameter_string = $this->oauth_http_build_query($params, true);
    $header = $this->build_oauth_header($params, "yahooapis.com");
    $headers[] = $header;
	} else {
    $query_parameter_string = $this->oauth_http_build_query($params);
	}
    $request_url = $url . ($query_parameter_string ?
                           ('?' . $query_parameter_string) : '' );
    $response = $this->curl($request_url, 443, $headers);
    $response = $this->oauth_parse_str($response);
    Zend_Debug::dump($response);
    exit;
    $token = $this->createToken($response);

	$this->cleanUp();
	return $response;
	}
	}


class OAuthUtil {
	public static function urlencode_rfc3986($input) {
	if (is_array($input)) {
		return array_map(array('OAuthUtil', 'urlencode_rfc3986'), $input);
	} else if (is_scalar($input)) {
	return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
	} else {
	return '';
	}
}
}