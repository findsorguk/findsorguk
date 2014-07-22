<?php
/** A class for interfacing with the Google Oauth system and getting a token
 * 
 * An example of code use:
 * <code>
 * <?php
 * $google = new Pas_Oauth_Google();
 * $google->setCallback($callback);
 * $google->setConsumerKey($key);
 * $google->setConsumerSecret($secret);
 * $google->generate();	
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @category Pas
 * @package Pas_Oauth
 * @example /app/modules/admin/controllers/OauthController.php
 * @uses \Zend_Registry
 * @uses \Zend_Session_Namespace
 * @uses \Zend_Oauth_Consumer
 * @uses \Zend_Controller_Front
 * @todo change set methods to instance of zend config and check if uri is 
 * correct format
 */
class Pas_Oauth_Google{
	
    /** The callback to use
     * @access protected
     * @var string
     */
    protected $_callback;

    /** Get the callback uri
     * @access public
     * @return string
     */
    public function getCallback() {
        return $this->_callback;
    }

    /** Set the callback uri
     * @access public
     * @param string $callback
     * @return \Pas_Oauth_Google
     * @todo check if uri 
     */
    public function setCallback($callback) {
        $this->_callback = Zend_Registry::get('siteurl') . $callback;
        return $this;
    }

    /** The consumer key to use
     * @access protected
     * @var string
     */
    protected $_consumerKey;
    
    /** Get the consumer key to use
     * @access protected
     * @return string The key to use with the oauth endpoint.
     */
    public function getConsumerKey() {
        return $this->_consumerKey;
    }

    /** Set the consumer key
     * @access public
     * @param type $consumerKey The consumer key string
     * @return \Pas_Oauth_Google
     */
    public function setConsumerKey($consumerKey) {
        $this->_consumerKey = $consumerKey;
        return $this;
    }

    /** The consumer secret
     * @access protected
     * @var string the Secret string to use.
     */
    protected $_consumerSecret;

    /** Get the consumer secret string
     * @access public
     * @return stringb
     */
    public function getConsumerSecret() {
        return $this->_consumerSecret;
    }

    /** Set the consumer secret string
     * @access public
     * @param string $consumerSecret
     * @return \Pas_Oauth_Google
     */
    public function setConsumerSecret($consumerSecret) {
        $this->_consumerSecret = $consumerSecret;
        return $this;
    }
    
    /** Request a token from twitter and authorise the app
     */
    public function generate(){
        $config = array(
            'requestTokenUrl' => 'https://www.google.com/accounts/OAuthGetRequestToken',
            'accessTokenUrl' => 'https://www.google.com/accounts/OAuthGetAccessToken',
            'userAuthorisationUrl' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
            'localUrl' => Zend_Registry::get('siteurl') . '/admin/oauth',
            'callbackUrl' => $this->getCallback(),
            'consumerKey' => $this->getConsumerKey(),
            'consumerSecret' => $this->getConsumerSecret(),
            'version' => '1.0', 
            'signatureMethod' => 'HMAC-SHA1',
        );
        $consumer = new Zend_Oauth_Consumer($config);
        $consumer->setAuthorizeUrl('https://www.google.com/accounts/OAuthAuthorizeToken');
        $token	= $consumer->getRequestToken();
        $session = new Zend_Session_Namespace('google_oauth');
        $session->token = $token->getToken();
        $session->secret = $token->getTokenSecret();
        $consumer->redirect();
    }

    /** Create the access token and save to database
     * @access public
     * @return void
     * 
     */
    public function access(){
        $config = array(
            'requestTokenUrl' => 'https://www.google.com/accounts/OAuthGetRequestToken',
            'accessTokenUrl' => 'https://www.google.com/accounts/OAuthGetAccessToken',
            'userAuthorisationUrl' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
            'localUrl' => Zend_Registry::get('siteurl') . '/admin/oauth',
            'callbackUrl' => $this->getCallback(),
            'consumerKey' => $this->getConsumerKey(),
            'consumerSecret' => $this->getConsumerSecret(),
            'version' => '1.0', 
            'signatureMethod' => 'HMAC-SHA1',
        );
        $session = new Zend_Session_Namespace('flickr_oauth');
        // build the token request based on the original token and secret
        $request = new Zend_Oauth_Token_Request();
        $request->setToken($session->token)->setTokenSecret($session->secret);
        unset($session->token);
        unset($session->secret);
        $consumer = new Zend_Oauth_Consumer($config);
        $token = $consumer->getAccessToken(
                Zend_Controller_Front::getInstance()->getRequest()->getQuery(),
                $request
                );
        $tokens = new OauthTokens();
        $tokenRow = $tokens->createRow();	
        $tokenRow->service = 'googleAccess';
        $tokenRow->accessToken = serialize($token);
        $tokenRow->created = Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');;
        $tokenRow->save();
    }

}