<?php
class Pas_Oauth_Google{
	
	protected $_callback;
	
	protected $_consumerKey;
	
	protected $_consumerSecret;
	
	protected $_config;
	
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_consumerKey = $this->_config->webservice->google->oauthconsumerkey;
	$this->_consumerSecret = $this->_config->webservice->google->oauthsecret;
	$this->_callback = Zend_Registry::get('siteurl') . '/admin/oauth/googleaccess';
	}

	/** Request a token from twitter and authorise the app
	 */
	public function generate(){

	$config = array(
	'requestTokenUrl' => 'https://www.google.com/accounts/OAuthGetRequestToken',
	'accessTokenUrl' => 'https://www.google.com/accounts/OAuthGetAccessToken',
	'userAuthorisationUrl' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
	'localUrl' => Zend_Registry::get('siteurl') . '/admin/oauth',
	'callbackUrl' => $this->_callback,
	'consumerKey' => $this->_consumerKey,
	'consumerSecret' => $this->_consumerSecret,
	'version' => '1.0', 
	'signatureMethod' => 'HMAC-SHA1',
	);
	$consumer	= new Zend_Oauth_Consumer($config);
	$consumer->setAuthorizeUrl('https://www.google.com/accounts/OAuthAuthorizeToken');
	$token	= $consumer->getRequestToken();
	$session = new Zend_Session_Namespace('google_oauth');
	$session->token  = $token->getToken();
	$session->secret = $token->getTokenSecret();
	$consumer->redirect();
	}

	/** Create the access token and save to database
	 * 
	 */
	public function access(){
	$config = array(
	'requestTokenUrl' => 'https://www.google.com/accounts/OAuthGetRequestToken',
	'accessTokenUrl' => 'https://www.google.com/accounts/OAuthGetAccessToken',
	'userAuthorisationUrl' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
	'localUrl' => Zend_Registry::get('siteurl') . '/admin/oauth',
	'callbackUrl' => $this->_callback,
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
	$tokenRow->service 		= 'googleAccess';
	$tokenRow->accessToken 		= serialize($token);
	$tokenRow->created 		= $now;
	$tokenRow->save();
	}
	
}