<?php
/** Model for creating Twitter oauth tokens and persist to storage in database
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $twitter = new Twitter();
 * $this->_redirect($twitter->request());
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @since 3 October 2011
 * @license GNU
 * @version 1
 * @example /app/modules/admin/controllers/OauthController.php
 */

class Twitter extends Pas_Db_Table_Abstract {
    
    /** The default table name 
     * @access protected
     * @var string
     */
    protected $_name = 'oauthTokens';
    
    /** Primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The callback for this site for authorisation
     * 
     */
    const CALLBACKURL = 'http://finds.org.uk/admin/oauth/twitteraccess';
    
    /** The twitter url
     * 
     */
    const TWITTER = 'http://twitter.com/oauth';
    
    /** The oauth model
     * @access protected
     * @var object
     */
    protected $_oauth;
    
    /** Get the oauth model
     * @access protected
     * @return \OauthTokens
     */
    public function getOauth() {
        $this->_oauth = new OauthTokens();
        return $this->_oauth;
    }
    
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

    /** Get the consumer key
     * @access public
     * @return string
     */
    public function getConsumerKey() {
        return $this->_consumerKey;
    }

    /** Get the consumer secret
     * @access public
     * @return string
     */
    public function getConsumerSecret() {
        return $this->_consumerSecret;
    }
    /** Set the consumer key
     * @access public
     * @param string $consumerKey
     * @return \Twitter
     */
    public function setConsumerKey($consumerKey) {
        $this->_consumerKey = $consumerKey;
        return $this;
    }

    /** Set the consumer key
     * @access public
     * @param string $consumerSecret
     * @return \Twitter
     */
    public function setConsumerSecret($consumerSecret) {
        $this->_consumerSecret = $consumerSecret;
        return $this;
    }
    
    /** Request a token from twitter and authorise the app
     * @access public
     * @throws Pas_Yql_Exception
     */
    public function request(){
        $tokens = $this->getOauth()->fetchRow($this->getOauth()->select()->where('service = ?', 'twitterAccess'));
        if(is_null($tokens)){
            $config = array(
                'callbackUrl' => self::CALLBACKURL,
                'siteUrl' => self::TWITTER,
                'consumerKey' => $this->getConsumerKey(),
                'consumerSecret' => $this->getConsumerSecret()
                    );
            $consumer = new Zend_Oauth_Consumer($config);
            $token = $consumer->getRequestToken();
            $tokenRow = $this->createRow();
            $tokenRow->service = 'twitterRequest';
            $tokenRow->created = Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
            $tokenRow->accessToken = serialize($token);
            $tokenRow->save();
            $consumer->redirect();
        } else {
            throw new Pas_Yql_Exception('Token already exists', 500);
        }
    }

    /** Create the access token and save to database
     * @access public
     * @return boolean
     * @throws Pas_Yql_Exception
     */
    public function access(){
        $config = array(
            'callbackUrl' => self::CALLBACKURL,
            'siteUrl' => self::TWITTER,
            'consumerKey' => $this->getConsumerKey(),
            'consumerSecret' => $this->getConsumerSecret()
        );
        $consumer = new Zend_Oauth_Consumer($config);
        $token = $this->getOauth()->fetchRow($this->getOauth()->select()->where('service = ?', 'twitterRequest'));
        // Get access token
        if(!is_null($token)) {
            $accessToken = $consumer->getAccessToken(Zend_Controller_Front::getInstance()->getRequest()->getQuery(),
            unserialize( $token['accessToken'] ) );
            $oauth_token  = $accessToken->getToken();
            $tokenRow = $this->createRow();
            $tokenRow->service	= 'twitterAccess';
            $tokenRow->created	= Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
            $tokenRow->accessToken = serialize($accessToken);
            $tokenRow->save();
            return true;
        } else {
            throw new Pas_Yql_Exception( 'Invalid access. No token provided.' );
        }
    }

}