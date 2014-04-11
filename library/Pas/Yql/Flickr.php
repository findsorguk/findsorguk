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
class Pas_Yql_Flickr {

	protected $_cache, $_oauth, $_accessToken, $_accessSecret;

 	protected $_accessExpiry, $_handle, $_flickr;

	public function __construct($flickr){
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
	$this->_flickr = $flickr;
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


	public function getContacts( $page = 1, $limit = 60 ){
	$args = array(
	'method' => 'flickr.contacts.getPublicList',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'per_page' => $limit,
	'page' => $page
	);
	$yql = 'select * from xml where url="' . self::FLICKRURI . $this->buildQuery($args) . '"';
	return $this->getData($yql)->query->results->rsp->contacts;
	}

	public function findByUsername( $name ){
	$yql = 'SELECT * FROM flickr.people.findbyusername WHERE username="' . $name . '" and api_key ="'
	. $this->_flickr->apikey  . '"';
	return $this->getData($yql)->query->results->user->nsid;
	}

	public function getContactDetails( $name ){
	$id = $this->findByUsername($name);
	$yql = 'SELECT * FROM flickr.people.info2 WHERE user_id="' . $id .'" AND api_key="' . $this->_flickr->apikey .'"';
	return $this->getData($yql)->query->results->person;
	}

	public function getContactPhotos( $name, $start = 0, $limit = 18 ){
	$id = $this->findByUsername($name);
	$yql = 'SELECT * FROM flickr.people.publicphotos(' . $start . ',' . $limit . ') WHERE user_id="' . $id .'" AND
	extras="' . $this->_extras . '" AND api_key="' . $this->_flickr->apikey .'"';
	return $this->getData($yql)->query->results->photo;
	}

	public function getPhotosGeoData( $start = 0, $limit = 200, $user_id ){
	$yql = 'select * from flickr.photos.search(' . $start . ',' . $limit .') where has_geo="true" and user_id="' .
	$user_id .'" and api_key="' . $this->_flickr->apikey .'" and extras="' . $this->_extras
	. '" and sort="date-posted-desc"';
	return $this->getData($yql)->query->results->photo;
	}

	public function getPhotoCommentList( $photoID, $mindate = NULL, $maxdate = NULL){
	$args = array(
	'method' => 'flickr.photos.comments.getList',
	'api_key' => $this->_flickr->apikey,
	'photo_id' => $photoID,
	'min_comment_date' => $mindate,
	'max_comment_date' => $maxdate
	);
	$yql = 'Select * from xml where url ="' . self::FLICKRURI . $this->buildQuery($args). '"';
	return $this->getData($yql)->query->results->rsp;
	}

	public function getPhotoInfo(  $photoID, $secret = NULL ){
	$args = array(
	'method' => 'flickr.photos.getInfo',
	'api_key' => $this->_flickr->apikey,
	'photo_id' => $photoID,
	'secret' => $secret
	);
	$yql = 'Select * from xml where url ="' . self::FLICKRURI . $this->buildQuery($args) . '"';
	return $this->getData($yql)->query->results->rsp;
	}

	public function getSizes ( $photoID ){
	$args = array(
	'method' => 'flickr.photos.getSizes',
	'api_key' => $this->_flickr->apikey,
	'photo_id' => $photoID
	);
	$yql = 'Select * from xml where url ="' . self::FLICKRURI . $this->buildQuery($args) . '"';
	return $this->getData($yql)->query->results->rsp->sizes;
	}

	public function getGeoLocation( $photoID ){
	$args = array(
	'method' => 'flickr.photos.geo.getLocation',
	'api_key' => $this->_flickr->apikey,
	'photo_id' => $photoID
	);
	$yql = 'Select * from xml where url ="' . self::FLICKRURI . $this->buildQuery($args) . '"';
	if(array_key_exists('photo', $this->getData($yql)->query->results->rsp )){
	return $this->getData($yql)->query->results->rsp->photo;
	} else {
		return false;
	}
	}
	public function getSetsList( $userid, $page = 1, $per_page = 10){
	$args = array(
	'method' => 'flickr.photosets.getList',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $userid,
	'page' => $page,
	'per_page' => $per_page
	);
	$yql = 'Select * from xml where url ="' . self::FLICKRURI . $this->buildQuery($args) . '"';
	return $this->getData($yql)->query->results->rsp;
	}

	public function getWoeidRadius( $woeid, $radius = NULL, $units = NULL, $per_page = 20, $page = 1, $tags, $license){
	$args = array(
	'method' => 'flickr.photos.search',
	'api_key' => $this->_flickr->apikey,
	'tags' => $tags,
	'license' => $license,
	'extras' => $this->_extras,
	'woe_id' => $woeid,
	'radius' => $radius,
	'radius_units' => $units,
	'has_geo' => true,
	'per_page' => $per_page,
	'page' => $page
	);
	$yql = 'Select * from xml where url ="' . self::FLICKRURI . $this->buildQuery($args) . '"';
	
	return $this->getData($yql)->query->results->rsp;
	}

	public function getPhotosetInfo( $photosetID ){
	$yql = 'SELECT * FROM flickr.photosets.info WHERE photoset_id="' . $photosetID . '" and api_key="'
	. $this->_flickr->apikey . '"';
	return $this->getData($yql);
	}

	public function getPhotosInASet( $setID, $per_page = 20, $page = 1 ){
	$args = array(
	'method' => 'flickr.photosets.getPhotos',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'page' => $page,
	'per_page' => $per_page,
	'photoset_id' => $setID,
	'extras' => $this->_extras,
	'media' => 'photos'
	);
	$yql = 'select * from xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '"';
	return $this->getData($yql)->query->results->rsp;
	}

	public function getPhotoExifDetails( $photoID ){
	$yql = 'select * from flickr.photos.exif where photo_id="' . $photoID . '" and api_key="'
	. $this->_flickr->apikey . '"';
	return $this->getData($yql)->query->results->photo;
	}

	public function getPhotosTaggedAs( $tag, $per_page = 20, $page = 1){
	$args = array(
	'method' => 'flickr.photos.search',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'extras' => $this->_extras,
	'tags' => $tag,
	'tag_mode' => 'all',
	'safe_search' => 1,
	'page' => $page,
	'per_page' => $per_page);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;
	}

	public function getArchaeology( $tag, $per_page = 20, $page = 1, $woeid = NULL){
	$args = array(
	'method' => 'flickr.photos.search',
	'api_key' => $this->_flickr->apikey,
	'extras' => $this->_extras,
	'tags' => $tag,
	'tag_mode' => 'all',
	'safe_search' => 1,
	'page' => $page,
	'per_page' => $per_page,
	'woe_id' => $woeid,
	'license' => '1,2,3,4,5,6,7');
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;
	}

	public function getInterestingPhotos($date, $per_page = 20, $page = 1){
	$args = array(
	'method' => 'flickr.interestingness.getList',
	'api_key' => $this->_flickr->apikey,
	'date' => $date,
	'extras' => $this->_extras,
	'per_page' => $per_page,
	'page' => $page
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;
	}

	public function getPublicFavourites( $min_fave_date = NULL, $max_fave_date = NULL, $per_page = 20, $page = 1){
	$args = array(
	'method' => 'flickr.favorites.getPublicList',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'min_fave_date' => $min_fave_date,
	'max_fave_date' => $max_fave_date,
	'per_page' => $per_page,
	'extras' => $this->_extras,
	'page' => $page
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;
	}

	public function getPopularPhotos($userid, $per_page = 20, $page = 1, $sort = 'views', $date){
	$args = array(
	'method' => 'flickr.stats.getPopularPhotos',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $userid,
	'sort' => $sort,
	'per_page' => $per_page,
	'page' => $page,
	'date' => $date
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;
	}

	public function getTagsListUserPopular( $userid , $count = 10 ){
	$args = array(
	'method' => 'flickr.tags.getListUserPopular',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $userid,
	'count' => $count
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->who->tags;
	}

	public function getTagsListUser( $userid ){
	$args = array(
	'method' => 'flickr.tags.getListUser',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $userid
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->tags;
	}

	public function getTagsListPhoto( $photo_id ){
	$args = array(
	'method' => 'flickr.tags.getListUser',
	'api_key' => $this->_flickr->apikey,
	'photo_id' => $photo_id
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;
	}

	public function getTotalViews ($date = NULL){
	$tokens = new OauthTokens();
	$tokenes = $tokens->fetchRow($tokens->select()->where('service = ?', 'flickrAccess'));
	$instance = unserialize($tokenes->accessToken);
	$oauthOptions = array(
	'requestTokenUrl' => 'http://www.flickr.com/services/oauth/request_token',
	'accessTokenUrl' => 'http://www.flickr.com/services/oauth/access_token',
	'userAuthorisationUrl' => 'http://www.flickr.com/services/oauth/authorize',
	'version' => '1.0',
	'signatureMethod' => 'HMAC-SHA1',
	'consumerKey' => $this->_flickr->apikey,
	'consumerSecret' => $this->_flickr->secret
	);
	$client = $instance->getHttpClient($oauthOptions);
	$client->setMethod(Zend_Http_Client::POST);
	$client->setUri(self::FLICKRURI);
	$client->setParameterPost('method', 'flickr.stats.getTotalViews');
	$client->setParameterPost('format', 'json');
	$client->setParameterPost('nojsoncallback',1);
	$response = $client->request();
	return json_decode($response->getBody());
	}
	
	public function getMachineTagged($tag, $per_page = 10, $page = 1){
	$args = array(
	'method' => 'flickr.photos.search',
	'api_key' => $this->_flickr->apikey,
	'machine_tags' => $tag,
	'machine_tag_mode' => 'all',
	'per_page' => $per_page,
	'page' => $page,
	'extras' => $this->_extras,
	);
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;	
	}
	
	public function searchPhotos( $term , $per_page = 10, $page = 1 ){
	$yql = 'select * from flickr.photos.search where text="' . $term . '" AND api_key="'
	. $this->_flickr->apikey .'" and extras="' . $this->_extras	. '" and sort="date-posted-desc"
	AND license="2,3,4,5,6" LIMIT 6';
	return $this->getData($yql)->query->results;		
	}

}
