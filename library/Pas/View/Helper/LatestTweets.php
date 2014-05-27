<?php
/**
 * This class is to retrieve tweets and display them.
 * 
 * This view helper is used to access Twitter's API via oauth and get back a 
 * number of tweets as specified. It is project specific and therefore not
 * for general use.
 * 
 * An example of how to use this
 * <code>
 * <?php 
 * echo $this->latestTweets()->setCount(2);
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_AutoLink
 * @uses Zend_View_Helper_Url
 * @uses Zend_Service_Twitter
 * @uses Zend_Registry
 * @uses Zend_Cache
 * @uses Pas_View_Helper_TimeAgoInWords
 * @uses OauthTokens
 * @example /app/modules/database/views/scripts/index/index.phtml 
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_LatestTweets extends Zend_View_Helper_Abstract
{

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;
    
    /** Number of tweets to retrieve
     * @access protected
     * @var int
     */
    protected $_count = 2;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** get the number of tweets to retrieve
     * @access public
     * @return int
     */
    public function getCount() {
        return $this->_count;
    }

    /** Set the count to retrieve
     * @access public
     * @param int $count
     * @return \Pas_View_Helper_LatestTweets
     */
    public function setCount($count) {
        $this->_count = $count;
        return $this;
    }

    /** The key for the cache
     * @access protected
     * @var type
     */
    protected $_key = 'twitterfindsorguk';

    /** Get the cache
     * @access public
     * @return type
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config
     * @access public
     * @return Zend_Config
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the key for the cache
     * @access public
     * @return string
     */
    public function getKey() {
        return $this->_key;
    }

    /** Call Twitter service
     *
     * @return array
     */
    private function _callTwitter() {
        if (!($this->getCache()->test(md5($this->getKey())))) {
            $tokens = new OauthTokens();
            $token = $tokens->fetchRow(
                    $tokens->select()->where('service = ?', 'twitterAccess')
                    );
            $twitter = new Zend_Service_Twitter(
                    array(
                        'username' => 'findsorguk',
                        'accessToken' => unserialize($token->accessToken),
                        'oauthOptions' => array(
                            'consumerKey' => $this->getConfig()->webservice->twitter->consumerKey,
                            'consumerSecret' => $this->getConfig()->webservice->twitter->consumerSecret
                                )
                        ));
            $tweets = $twitter->statusesUserTimeline(array(
                'count' => $this->getCount()
                ))->toValue();

            $this->getCache()->save($tweets);

        } else {
            $tweets = $this->getCache()->load(md5( $this->getKey() ));

        }
        return $this->buildHtml($tweets);
    }

    /** Build html string
     *
     * @param  array  $tweets
     * @return string
     */
    public function buildHtml($tweets) {
        $html = '';
        $html .= '<ul>';
        foreach ($tweets as $post) {
            $html .= '<li><strong>'. $this->view->timeAgoInWords($post->created_at);
            $html .= '</strong>';
            $html .= '<strong><a href="http://www.twitter.com/';
            $html .= $post->user->screen_name	. '">';
            $html .= $post->user->screen_name . '</a></strong> said: ';
            $html .= $this->view->autoLink()->setText($post->text)	. '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /** Get the tweets
     *
     * @return \Pas_View_Helper_LatestTweets
     */
    public function latestTweets()
    {
        return $this;
    }

    /** Magic method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_callTwitter();
    }
}