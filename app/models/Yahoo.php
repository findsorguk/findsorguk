<?php
/** 
 * Model for creating Yahoo oauth tokens
 * 
 * An example of use:
 * 
 * <code>
 * 
 * </code>
 * 
 * @category Pas
 * @package Db_Table
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @since 3 October 2011
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/OauthController.php 
 * @see https://developer.yahoo.com/oauth/guide/
 */

class Yahoo extends Pas_Db_Table_Abstract {
    
    /** The default table name
     * @access protected
     * @var string
     */
    protected $_name = 'oauthTokens';
    
    /** Primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The Yahoo url for getting a token
     * @var string
     */
    const YAHOOTOKENGET = 'https://api.login.yahoo.com/oauth/v2/get_token';

    /** The Yahoo link for requesting a token
     * @var string
     */
    const OAUTHYAHOO = 'https://api.login.yahoo.com/oauth/v2/get_request_token';

    /** The Yahoo url for requesting authorisation
     * @var string
     */
    const OAUTHYAHOOREQ = 'https://api.login.yahoo.com/oauth/v2/request_auth?';

    /** The callback for this site for authorisation
     *
     * @var string
     */
    const SITEYAHOOCALLBACK = 'http://beta.finds.org.uk/admin/oauth/yahooaccess/';

    /** The consumer key
     * @access protected
     * @var string
     */
    protected $_consumerKey;

    /** The consumer secret
     * @access protected
     * @var string
     */
    protected $_consumerSecret;

    /** The tokens
     * @access protected
     * @var type 
     */
    protected $_tokens;
    
    /** Date
     * @access protected
     * @var type 
     */
    protected $_date;
    
    /** Get the date and time and add one hour
     * @access public
     * @return array
     */
    public function getDate() {
        $this->_date = new Zend_Date();
        $this->_date->add('1', Zend_Date::HOUR)->toString('YYYY-MM-dd HH:mm:ss');
        return $this->_date;
    }

        
    /** Get the access keys, this could be changed to a constuct for passing keys
     * Uses the config.ini values
     *
     */
    public function init(){
        $this->_consumerKey = $this->_config->webservice->ydnkeys->consumerKey;
        $this->_consumerSecret = $this->_config->webservice->ydnkeys->consumerSecret;
    }

    /** Request a token from Yahoo
     * @access public
     * @return string $url The formed url for yahoo oauth request to be redirected to in controller.
     */
    public function request(){
        $config = array(
            'version' => '1.0',
            'requestScheme' => Zend_Oauth::REQUEST_SCHEME_HEADER,
            'signatureMethod' => 'HMAC-SHA1',
            'callbackUrl' => self::SITEYAHOOCALLBACK,
            'siteUrl' => self::OAUTHYAHOO,
            'consumerKey' => $this->_consumerKey,
            'consumerSecret' => $this->_consumerSecret,
        );
        $tokens = new OauthTokens();
        $tokenexists = $tokens->fetchRow($tokens->select()->where('service = ?', 'yahooAccess'));
        if(is_null($tokenexists)){
            $consumer = new Zend_Oauth_Consumer($config);
            $token = $consumer->getRequestToken();
            $session = new Zend_Session_Namespace('yahoo_oauth');
            $session->token  = $token->getToken();
            $session->secret = $token->getTokenSecret();
            $urlParams = $token->getResponse()->getBody();
            $url = self::OAUTHYAHOOREQ . $urlParams;
            return $url;
        } else {
            throw new Pas_Yql_Exception('Token exists');
        }
    }

    /** Create the token for yahoo access and save to database.
     * @access public
     * @return array
     */
    public function access(){
        $config = array(
            'siteUrl' => self::YAHOOTOKENGET,
            'callbackUrl' => 'http://beta.finds.org.uk/admin/oauth/',
            'consumerKey' => $this->_consumerKey,
            'consumerSecret' => $this->_consumerSecret,
        );
        $session = new Zend_Session_Namespace('yahoo_oauth');
        // build the token request based on the original token and secret
        $request = new Zend_Oauth_Token_Request();
        $request->setToken($session->token)->setTokenSecret($session->secret);
        unset($session->token);
        unset($session->secret);
        $consumer = new Zend_Oauth_Consumer($config);
        $token = $consumer->getAccessToken($_GET, $request);
        return $this->buildToken($token);
    }
    
    /** Refresh access using old details
     * @access public
     * @param string $old_access_token
     * @param string $old_token_secret
     * @param string $oauth_session_handle
     * @return \build_token
     */
    public function refreshAccess( $old_access_token, $old_token_secret, 
	$oauth_session_handle ) {
         $config = array(
            'siteUrl' => self::YAHOOTOKENGET,
            'callbackUrl' => 'http://beta.finds.org.uk/admin/oauth/',
            'consumerKey' => $this->_consumerKey,
            'consumerSecret' => $this->_consumerSecret,
             
        );
        $session = new Zend_Session_Namespace('yahoo_oauth');
        // build the token request based on the original token and secret
        $request = new Zend_Oauth_Token_Request();
        $request->setToken($session->token)->setTokenSecret($session->secret);
        unset($session->token);
        unset($session->secret);
        $consumer = new Zend_Oauth_Consumer($config);
        $token = $consumer->getAccessToken($_GET, $request);
        return $this->buildToken($token);
    }
    
    /** Build a token
     * @access public
     * @param object $token
     * @return boolean
     */
    public function buildToken( $token ) {
        $expires = $this->getDate();
        $oauth_guid = $token->xoauth_yahoo_guid;
        $oauth_session = $token->oauth_session_handle;
        $oauth_token = $token->getToken();
        $oauth_token_secret = $token->getTokenSecret();
        $tokenRow = $this->createRow();
        $tokenRow->service = 'yahooAccess';
        $tokenRow->accessToken = serialize($oauth_token);
        $tokenRow->tokenSecret = serialize($oauth_token_secret);
        $tokenRow->guid = serialize($oauth_guid);
        $tokenRow->sessionHandle = serialize($oauth_session);
        $tokenRow->created = $this->timeCreation();
        $tokenRow->expires = $expires;
        $tokenRow->save();
        return true;
    }
}

