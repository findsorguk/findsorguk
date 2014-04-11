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
class Pas_Yql_Flickr_Private {
	
	protected $_cache, $_oauth, $_flickr;
	
	const FLICKRURI = 'http://api.flickr.com/services/rest/?';
	
	public function __construct($flickr){
	$this->_cache = Zend_Registry::get('cache');
	$this->_flickr = $flickr;	
	}
	
	private function options(){
	$oauthOptions = array(
	'requestTokenUrl' => 'http://www.flickr.com/services/oauth/request_token',
	'accessTokenUrl' => 'http://www.flickr.com/services/oauth/access_token',
	'userAuthorisationUrl' => 'http://www.flickr.com/services/oauth/authorize',
	'version' => '1.0',
	'signatureMethod' => 'HMAC-SHA1',
	'consumerKey' => $this->_flickr->apikey,
	'consumerSecret' => $this->_flickr->secret
	);
	return $oauthOptions;
	}
	
	protected $_extras = 'description,license,date_upload,date_taken,owner_name,icon_server,original_format,
	last_update,geo,tags,machine_tag,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m,url_o';
	
	public function getTokens(){
	$tokens = new OauthTokens();
	$tokenes = $tokens->fetchRow($tokens->select()->where('service = ?', 'flickrAccess'));
	return unserialize($tokenes->accessToken);
	}
	
	public function getData($args){
	$client = $this->getTokens()->getHttpClient($this->options);
	$client->setUri(self::FLICKRURI);
	$client->setParameterPost($args);
	$client->setParameterPost('format', 'json');
	$client->setParameterPost('nojsoncallback',1);
	$response = $client->request();
	return json_decode($response->getBody());	
	}
	public function getTotalViews ($date = NULL){
	$args = array(
	'method' => 'flickr.stats.getTotalViews',
	'date' => $date
	);
	return $this->getData($args);
	}
	
	public function getRecentCommentsForContacts ($date_lastcomment  = NULL, $contacts_filter = NULL, $per_page = 20, 
	$page =1){
	$args = array(
	'method' => 'flickr.photos.comments.getRecentForContacts',
	'date_lastcomment' => $date_lastcomment,
	'contacts_filter' => $contacts_filter,
	'per_page' => $per_page,
	'page' => $page,
	'extras' => $this->_extras);
	return $this->getData($args);
	}
	
}
