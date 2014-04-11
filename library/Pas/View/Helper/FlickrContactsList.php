<?php
/** A view helper for displaying flickr contacts list
 * Could be abstracted to a flickr class
 * @version 1
 * @since 7 October 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Oauth
 */
class Pas_View_Helper_FlickrContactsList 
	extends Zend_View_Helper_Abstract {
	
	protected $_cache;
	
	protected $_flickrKey;
	
	protected $_userID;
	
	protected $_oauth;
	
	protected $_flickr;
	
	public function __construct(  )  { 
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
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
	
	/** get the flickr data based on access keys
	 * @todo abstract this to use Pas_Yql_Flickr
	 * @param $access
	 */
	public function getFlickr($access){
	$key = md5 ('flickrcontactslist');
	if (!$friends = $this->_cache->load($key)) {
	$contacts = $this->_flickr->getContacts(1,60);
	$this->_cache->save($contacts);
	} else {
	$contacts= $this->_cache->load($key);
	}
	$total = (int)$contacts->total;
	return $this->buildHtml($contacts, $total);
	} 
	
	/** Create the html
	 * 
	 * @param array $contacts The contacts array
	 * @param int $total The total number of contacts to display
	 */
	public function buildHtml($contacts, $total){
	$key = md5 ('contactslistFP');
	if (!$friends = $this->_cache->load($key)) {
	$html = '<h3>Our flickr contacts</h3>';
	foreach($contacts->contact as $c){
	$type = '.jpg';
	$url = 'http://farm'. $c->iconfarm . '.static.flickr.com/' . $c->iconserver . '/buddyicons/' . $c->nsid . $type;
	$alturl = 'http://www.flickr.com/images/buddyicon.jpg';
	$link = $this->view->url(array('module' => 'flickr', 'controller' => 'contacts', 'action' => 'known', 'as' => $c->username),'default',true);
	if($c->iconfarm != 0) {
	$html .= '<a href="' . urldecode($link) .'" title="Go to ' . $c->username . '\'s profile on flickr" rel="friend nofollow"><img src="' 
	. $url . '" height="48" width="48" alt="View ' 
	. $c->username . '\'s images" /></a>';
	} else {
	$html .= '<a href="' . $link . '" title="Go to this ' . $c->username . '\'s profile on flickr" rel="friend nofollow"><img src="' 
	. $alturl . '" height="48" width="48" alt="View ' 
	. $c->username . '\'s images" /></a>';
	}
	}
	$contactsurl = $this->view->url(array('module' => 'flickr','controller' => 'contacts'),'default',true);
	$html .= '<p>View our <a href="' . $contactsurl . '" title="View our contacts">' . $total . '</a> friends and their images &raquo;</p>';
	$this->_cache->save($html);
	} else {
	$html = $this->_cache->load($key);
	}
	return $html;
	}
	
	/** Get the data via flickr yql call class
	 * 
	 * @param object $flickr
	 */
	public function flickrContactsList($flickr) {
	$this->_flickr = new Pas_Yql_Flickr($flickr);
	$openup = $this->getAccessKeys();
	if(!is_null($openup)){
	return $this->getFlickr($openup);
	} else {
	return false;	
	}
	}
}

