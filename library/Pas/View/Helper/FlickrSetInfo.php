<?php
/** A view helper for displaying info about a flickr set
 * Could be abstracted to a flickr class
 * @version 1
 * @since 7 October 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Zend_View_Helper_HeadTitle
 * @uses Pas_View_Helper_Metabase
 * @uses Pas_Yql_Oauth
 */
class Pas_View_Helper_FlickrSetInfo 
	extends Zend_View_Helper_Abstract {
	
    protected $_cache;
    protected $_oauth;
	protected $_accessToken;
	protected $_accessSecret;
	protected $_accessExpiry;
	protected $_handle;
	protected $_flickrKey;
	protected $_config;
	protected $_flickrSecret;
	protected $_flickrAuth;

	/** Construct the objects
	 * 
	 */
    public function __construct() {
    $this->_cache = Zend_Registry::get('rulercache');
	$this->_oauth = new Pas_Yql_Oauth();
	$tokens = new OauthTokens();
    $where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
	$validToken = $tokens->fetchRow($where);
	$this->_accessToken= unserialize($validToken->accessToken);
	$this->_accessSecret = unserialize($validToken->tokenSecret);
	$this->_accessExpiry = $validToken->expires;
	$this->_handle = unserialize($validToken->sessionHandle);
	$this->_config = Zend_Registry::get('config');
	$this->_flickrKey = $this->_config->webservice->flickr->apikey;
	$this->_flickrSecret = $this->_config->webservice->flickr->secret ;
	$this->_flickrAuth = $this->_config->webservice->flickr->auth;
    }	

    /** Return the info about a flickr set and set head title and metadata
     * @param int $id Flickr set ID
     */
	public function FlickrSetInfo($id) {
	if (!($this->_cache->test('flickrSet' . $id))) {
	$query = 'SELECT * FROM flickr.photosets.info WHERE photoset_id="' . $id .'" and api_key="' . $this->_flickrKey . '";';
	$flickr = $this->_oauth->execute($query,$this->_accessToken, 
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('flickrSet' . $id);
	}
	$this->view->headTitle('All photos in the set titled: ' . $flickr->query->results->photoset->title);
	$this->view->MetaBase($flickr->query->results->photoset->description, 
						  'photos', 
						  'archaeology, photos, portable antiquities ');
	return '<h2>' . $flickr->query->results->photoset->title . '</h2>';
	
	}


}