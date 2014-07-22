<?php
/** A class for interfacing with the Flickr Oauth system and getting a token
 * 
 * An example of code use:
 * <code>
 * <?php
 * $flickr = new Pas_Oauth_Flickr();
 * $flickr->setCallback($callback);
 * $flickr->setConsumerSecret($secret);
 * $flickr->setConsumerKey($key);
 * $flickr->generate();	
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
 * @todo change set methods to instance of zend config
 * 
 */
class Pas_Oauth_Flickr {
	
    /** The callback uri
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

    public function setCallback($callback) {
        $this->_callback = Zend_Registry::get('siteurl') . $callback;
        return $this;
    }
    
    /** The consumer key
     * @access protected
     * @var string
     */
    protected $_consumerKey;

    /** Get the consumer key
     * @access public
     * @return string
     */
    public function getConsumerKey() {
        return $this->_consumerKey;
    }

    /** Set the consumer key
     * @access public
     * @param type $consumerKey
     * @return \Pas_Oauth_Flickr
     */
    public function setConsumerKey($consumerKey) {
        $this->_consumerKey = $consumerKey;
        return $this;
    }

    /** The consumer secret
     * @access protected
     * @var string
     */
    protected $_consumerSecret;

    /** Get the consumer secret
     * @access public
     * @return string
     * 
     */
    public function getConsumerSecret() {
        return $this->_consumerSecret;
    }

    /** Set the consumer secret
     * @access public
     * @param type $consumerSecret
     * @return \Pas_Oauth_Flickr
     */
    public function setConsumerSecret($consumerSecret) {
        $this->_consumerSecret = $consumerSecret;
        return $this;
    }

    /** Request a token from flickr and authorise the app
     * @access public
     * @return void
     */
    public function generate(){
        $config = array(
        'requestTokenUrl' => 'http://www.flickr.com/services/oauth/request_token',
        'accessTokenUrl' => 'http://www.flickr.com/services/oauth/access_token',
        'userAuthorisationUrl' => 'http://www.flickr.com/services/oauth/authorize',
        'localUrl' => Zend_Registry::get('siteurl') . '/admin/oauth',
        'callbackUrl' => $this->getCallback(),
        'consumerKey' => $this->getConsumerKey(),
        'consumerSecret' => $this->getCallback(),
        'version' => '1.0', 
        'signatureMethod' => 'HMAC-SHA1',
        );
        $consumer = new Zend_Oauth_Consumer($config);
        $consumer->setAuthorizeUrl('http://www.flickr.com/services/oauth/authorize');
        $token	= $consumer->getRequestToken();
        $session = new Zend_Session_Namespace('flickr_oauth');
        $session->token  = $token->getToken();
        $session->secret = $token->getTokenSecret();
        $consumer->redirect($customServiceParameters = array(
            'perms' => 'delete')
                );
    }

    /** Create the access token and save to database
     * @access public
     * @return void
     */
    public function access(){
        $config = array(
        'requestTokenUrl' => 'http://www.flickr.com/services/oauth/request_token',
        'accessTokenUrl' => 'http://www.flickr.com/services/oauth/access_token',
        'userAuthorisationUrl' => 'http://www.flickr.com/services/oauth/authorize',
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
        $tokenRow->service = 'flickrAccess';
        $tokenRow->accessToken = serialize($token);
        $tokenRow->created = Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
        $tokenRow->save();
    }
}