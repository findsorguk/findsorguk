<?php
class Pas_Oauth_Flickr {
	
	const CALLBACKURL = 'http://beta.finds.org.uk/admin/oauth/flickraccess';
	
	protected $_consumerKey;
	
	protected $_consumerSecret;
	
	protected $_config;
	
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_consumerKey = $this->_config->webservice->flickr->apikey;
	$this->_consumerSecret = $this->_config->webservice->flickr->secret;
	}

	/** Request a token from twitter and authorise the app
	 */
	public function generate(){

	$config = array(
	'requestTokenUrl' => 'http://www.flickr.com/services/oauth/request_token',
	'accessTokenUrl' => 'http://www.flickr.com/services/oauth/access_token',
	'userAuthorisationUrl' => 'http://www.flickr.com/services/oauth/authorize',
	'localUrl' => 'http://beta.finds.org.uk/admin/oauth',
	'callbackUrl' => self::CALLBACKURL,
	'consumerKey' => $this->_consumerKey,
	'consumerSecret' => $this->_consumerSecret,
	'version' => '1.0', 
	'signatureMethod' => 'HMAC-SHA1',
	);
	$consumer	= new Zend_Oauth_Consumer($config);
	$consumer->setAuthorizeUrl('http://www.flickr.com/services/oauth/authorize');
	$token	= $consumer->getRequestToken();
	$session = new Zend_Session_Namespace('flickr_oauth');
	$session->token  = $token->getToken();
	$session->secret = $token->getTokenSecret();
	$consumer->redirect($customServiceParameters = array('perms' => 'delete'));
	}

	/** Create the access token and save to database
	 * 
	 */
	public function access(){
	$config = array(
	'requestTokenUrl' => 'http://www.flickr.com/services/oauth/request_token',
	'accessTokenUrl' => 'http://www.flickr.com/services/oauth/access_token',
	'userAuthorisationUrl' => 'http://www.flickr.com/services/oauth/authorize',
	'localUrl' => 'http://beta.finds.org.uk/admin/oauth',
	'callbackUrl' => self::CALLBACKURL,
	'consumerKey' => $this->_consumerKey,
	'consumerSecret' => $this->_consumerSecret,
	'version' => '1.0', 
	'signatureMethod' => 'HMAC-SHA1',
	);
	$session = new Zend_Session_Namespace('flickr_oauth');
	// build the token request based on the original token and secret
	$request = new Zend_Oauth_Token_Request();
	$request->setToken($session->token)->setTokenSecret($session->secret);
	unset($session->token);
	unset($session->secret);
	$now = Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
	$date = new Zend_Date();
	$consumer = new Zend_Oauth_Consumer($config);
	$token = $consumer->getAccessToken(Zend_Controller_Front::getInstance()->getRequest()->getQuery(), $request);

	$tokens = new OauthTokens();
	$tokenRow = $tokens->createRow();	
	$tokenRow->service 		= 'flickrAccess';
	$tokenRow->accessToken 		= serialize($token);
	$tokenRow->created 		= $now;
	$tokenRow->save();
	}
	
}