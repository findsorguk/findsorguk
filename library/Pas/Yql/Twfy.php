<?php
/** A class for accessing the flickr api via authenticated YQL calls
 * 
 * @author Daniel Pett
 * @version 1
 * @since 18 October 2011
 * @license GNU Public
 * @category Pas
 * @package Pas_Yql
 * @uses Pas_Yql_Oauth
 * @see http://www.flickr.com/services/api/
 *
 */
class Pas_Yql_Twfy {
	
	protected $_cache;
	
	protected $_twfy;
	
	public function __construct($twfy){
	$this->_cache = Zend_Registry::get('cache');
	$this->_twfy = $this->set_twfy($twfy);
	}
	/** Oauth endpoint for YQL
	 * 
	 * @var string
	 */
	const API_URI = 'http://where.yahooapis.com/v1/';
	
	const COMMUNITY = 'store://datatables.org/alltableswithkeys';
	/** The Flickr URL for query formation
	 * 
	 * @var string
	 */
	const FLICKRURI = 'http://api.flickr.com/services/rest/?';
	
	/** Build the query string
	 * @param array $args
	 * @return string
	 */
	/**
	 * @return the $_twfy
	 */
	public function get_twfy() {
		return $this->_twfy;
	}

	/**
	 * @param $_twfy the $_twfy to set
	 */
	public function set_twfy($_twfy) {
		$this->_twfy = $_twfy;
	}

	public function buildQuery($args){
	return http_build_query($args);
	}
	
	protected $_extras = 'description,license,date_upload,date_taken,owner_name,icon_server,original_format,
	last_update,geo,tags,machine_tag,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m,url_o';
	
	public function getTokens(){
	$tokens = new OauthTokens();
	$where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
	$validToken = $tokens->fetchRow($where);
	$this->_accessToken= unserialize($validToken->accessToken);
	$this->_accessSecret = unserialize($validToken->tokenSecret);
	$this->_accessExpiry = $validToken->expires;
	$this->_handle = unserialize($validToken->sessionHandle);
	}
	
	public function getData($yql){
	return $this->_oauth->execute($yql, $this->_accessToken, $this->_accessSecret, $this->_accessExpiry, $this->_handle);
	}
	
	public function getHansard($term,$count = 20, $page, $order = 'd'){
	$args = array(
	'method' => 'flickr.contacts.getPublicList',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'per_page' => $limit,
	'page' => $page
	);	
	}
	
	
	
}
