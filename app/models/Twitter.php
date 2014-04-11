<?php
/** Model for creating Twitter oauth tokens and persist to storage in database
 * @category Pas
 * @package Pas_Db_Table
 * @since 3 October 2011
 * @license GNU
 * @author dpett
 * @copyright Daniel Pett
 * @version 1
 */

class Twitter extends Pas_Db_Table_Abstract {
	/** The default table name 
	 */
	protected $_name = 'oauthTokens';
	/** Primary key
	 * 
	 * @var int
	 */
	protected $_primary = 'id';
	
	/** The callback for this site for authorisation
	 * 
	 * @var string
	 */
	const CALLBACKURL = 'http://beta.finds.org.uk/admin/oauth/twitteraccess';
	
	protected $_consumerKey;
	
	protected $_consumerSecret;
	
	protected $_tokens;
	
	/** Get the access keys, this could be changed to a constuct for passing keys
	 * Uses the config.ini values
	 * 
	 */
	public function __construct(){
	parent::__construct();
	}

	/** Request a token from twitter and authorise the app
	 */
	public function request(){
	$tokens = new OauthTokens();
	$tokenexists = $tokens->fetchRow($tokens->select()->where('service = ?', 'twitterAccess'));
	if(is_null($tokenexists)){
	$config = array(
	'callbackUrl' => self::CALLBACKURL,
	'siteUrl' => 'http://twitter.com/oauth',
	'consumerKey' => $this->_config->webservice->twitter->consumerKey,
	'consumerSecret' => $this->_config->webservice->twitter->consumerSecret
	);
	$consumer			= new Zend_Oauth_Consumer($config);
	$token				= $consumer->getRequestToken();
	$secret				= serialize($token);
	$tokenRow			= $this->createRow();
	$tokenRow->service 		= 'twitterRequest';
	$tokenRow->created 		= Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
	$tokenRow->accessToken		= serialize($token);
	$tokenRow->save();
	$consumer->redirect();
	} else {
		throw new Pas_Yql_Exception('Token already exists');
	}
	}

	/** Create the access token and save to database
	 * 
	 */
	public function access(){
	$config = array(
	'callbackUrl' => self::CALLBACKURL,
	'siteUrl' => 'http://twitter.com/oauth',
	'consumerKey' => $this->_config->webservice->twitter->consumerKey,
	'consumerSecret' => $this->_config->webservice->twitter->consumerSecret
	);
	$consumer = new Zend_Oauth_Consumer($config);
 	$tokens = new OauthTokens();
	$token = $tokens->fetchRow($tokens->select()->where('service = ?', 'twitterRequest'));
    // Get access token
   	if(!is_null($token)) {
    $accessToken = $consumer->getAccessToken(Zend_Controller_Front::getInstance()->getRequest()->getQuery(),
     unserialize( $token['accessToken'] ) );
	$oauth_token 		= $accessToken->getToken();
	$tokenRow		= $this->createRow();
	$tokenRow->service	= 'twitterAccess';
	$tokenRow->created	= Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
	$tokenRow->accessToken	= serialize($accessToken);
	$tokenRow->save();
	return true;
    } else {
        throw new Pas_Yql_Exception( 'Invalid access. No token provided.' );
    }
	}

}