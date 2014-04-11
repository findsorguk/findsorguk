<?php
/**
 * A view helper for displaying html list of flickr images
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_Title
 * @uses Pas_View_Helper_CurUrl
 * @uses Zend_View_Helper_Baseurl
 */
class Pas_View_Helper_FlickrFront extends Zend_View_Helper_Abstract {
	
	protected $_cache;
	
	protected $_flickrKey;
	
	protected $_userID;
	
	
	public function __construct(  )  { 
	$this->_cache = Zend_Registry::get('cache');
	}
	/** Get the access keys for oauth
	 * 
	 */
	private function getAccessKeys() {
	$tokens = new OauthTokens();
    $where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
	$validToken = $tokens->fetchRow($where);
	if(!is_null($validToken)) {
	$access = array(
	'access_token' => unserialize($validToken->accessToken),
	'access_token_secret' => unserialize($validToken->tokenSecret),
	'access_token_expiry' => $validToken->expires,
	'handle' => unserialize($validToken->sessionHandle)
	);
	return $access;
	} else {
	return false;	
	}
	}
	
	/** Get response from Flickr YQL
	 * @uses Pas_YqlOauth
	 * @param object $access
	 */
	private function getFlickr($access) {
	$access = (object)$access;
	$key = 'flickrfontrecent';
	if (!($this->_cache->test($key))) {
	$oauth = new Pas_Yql_Oauth();
	$q = 'SELECT * FROM flickr.photos.search WHERE tag_mode ="all" AND user_id="' . $this->_userID
	. '" AND extras="description,geo,license,url_sq,url_m" and api_key="' . $this->_flickrKey . '" LIMIT 12';
    $data = $oauth->execute($q, $access->access_token, $access->access_token_secret, $access->access_token_expiry, 
    $access->handle);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($key);
	}	
	if(is_array((array)$data)){
	return $this->parseFlickr($data);
	} else {
		return false;
	}
	}
	
	/** Parse the flickr response to an array and build html
	 * 
	 * @param unknown_type $data
	 
	 */
	private function parseFlickr($data) {
	if(!is_null($data)){	
	$recent = array();
	foreach($data->query->results->photo as $k) {
	$recent[] = $k;
	}
	return $this->buildHtml($recent);
	} else {
		return false;
	}
	}

	/** Build the html from the flickr array
	 * 
	 * @param array $recent
	 */
	public function buildHtml($recent) {
	$html = '<div class="container"><section id="carousel"><div class="span10">';
	$html .= '<div id="myCarousel" class="carousel slide"><div class="carousel-inner">';
	$html .= '<div class="item active">';
	$html .= '<img src="http://farm7.staticflickr.com/6051/6266119088_ca20f47e2d_b.jpg" alt="">
                <div class="carousel-caption">
                  <h4>A pile of radiates</h4>
                  <p>Probus stands out on this pile of radiates &raquo;</p>
                </div>
              </div>
              <div class="item">
                <img src="http://farm6.staticflickr.com/5260/5395524318_60ebafbd33_b.jpg" alt="">
                <div class="carousel-caption">
                  <h4>Geoff Egan, our late colleague</h4>
                  <p>A lovely image of our friend and colleague, Geoff Egan.</p>
                </div>
              </div>
              <div class="item">
                <img src="http://farm6.staticflickr.com/5147/5621551253_68547bc4de_b.jpg" alt="">
                <div class="carousel-caption">
                  <h4>The Hackney Hoard</h4>
                  <p>An interesting Treasure case, with a human touch. The Hackney hoard relates to the sad
                  story of the Sulzbacher family</p>
                </div>
              </div>
              <div class="item">
                <img src="http://farm5.staticflickr.com/4131/5200670881_373ed82088_b.jpg" alt="">
                <div class="carousel-caption">
                  <h4>The magnificent Frome hoard</h4>
                  <p>150 kg of Roman coins were found by detectorist Dave Crisp.</p>
                </div>
              </div>
            </div>
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
          </div></div></div>';
	return $html;
	}
	
	public function flickrFront(  $flickr ) {
	$this->_flickrKey = $flickr->apikey;
	$this->_userID = $flickr->userid;
	$openup = $this->getAccessKeys();
	if(!is_null($openup)){
		return $this->getFlickr($openup);
	} else {
	return false;	
	}
	}
	
}

