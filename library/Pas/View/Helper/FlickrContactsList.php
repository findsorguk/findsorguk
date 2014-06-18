<?php
/**
 *  A view helper for displaying flickr contacts list
 *
 * A rubbish view helper that needs rewriting completely, but I don't have time.
 * This could be improved by sorting out URL generation.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->flickrContactsList()->setFlickr($flickr);
 * ?>
 * </code>
 *
 * @todo Could be abstracted to a flickr class
 * @version 1
 * @since 7 October 2011
 * @copyright 2014, Daniel Pett
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Oauth
 * @example /app/modules/flickr/views/scripts/index/index.phtml
 */
class Pas_View_Helper_FlickrContactsList extends Zend_View_Helper_Abstract {

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The oauth class for interfacing with flickr
     * @access protected
     * @var type
     */
    protected $_oauth;

    /** The flickr api wrapper
     * @access protected
     * @var type
     */
    protected $_flickr;

    /** Get the flickr wrapper
     * @access public
     * @return type
     */
    public function getFlickr() {
        return $this->_flickr;
    }

    /** Set the flickr wrapper up with YQL access
     * @access public
     * @param type $flickr
     * @return \Pas_View_Helper_FlickrContactsList
     */
    public function setFlickr($flickr) {
        $this->_flickr = new Pas_Yql_Flickr($flickr);
        return $this;
    }

    /** Get the cach object
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the oauth class
     * @access public
     * @return \Pas_Yql_Oauth
     */
    public function getOauth() {
        $this->_oauth = new Pas_Yql_Oauth();
        return $this->_oauth;
    }

    /** Get the oauth access token
     * @access public
     * @return boolean
     */
    public function getAccessKeys() {
        $tokens = new OauthTokens();
        $where = array();
        $where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess');
        $validToken = $tokens->fetchRow($where);
        if (!is_null($validToken)) {
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


    /** Get the list of contacts
     * @access public
     * @param stdClass $access
     * @return string
     */
    public function getContacts($access) {
        $key = md5 ('flickrcontactslist');
        if (!$friends = $this->getCache()->load($key)) {
            $contacts = $this->getFlickr()->getContacts(1,60);
            $this->getCache()->save($contacts);
        } else {
            $contacts= $this->getCache()->load($key);
        }
        $total = (int) $contacts->total;
        return $this->buildHtml($contacts, $total);
    }


    /** Build the html
     * @access public
     * @param stdClass $contacts
     * @param int $total
     * @return string
     */
    public function buildHtml( $contacts, $total)  {
        $html = '';
        $key = md5 ('contactslistFP');
        if (!$friends = $this->getCache()->load($key)) {
            $html = '<h3>Our flickr contacts</h3>';
            foreach ($contacts->contact as $c) {
                $type = '.jpg';
                $url = 'http://farm';
                $url .= $c->iconfarm;
                $url .= '.static.flickr.com/';
                $url .= $c->iconserver;
                $url .= '/buddyicons/';
                $url .= $c->nsid;
                $url .= $type;

                $alturl = 'http://www.flickr.com/images/buddyicon.jpg';

                $link = $this->view->url(array(
                    'module' => 'flickr',
                    'controller' => 'contacts',
                    'action' => 'known',
                    'as' => $c->username),'default',true);

                if ($c->iconfarm != 0) {
                    $html .= '<a href="';
                    $html .= urldecode($link);
                    $html .= '" title="Go to ';
                    $html .= $c->username;
                    $html .= '\'s profile on flickr" rel="friend nofollow">';
                    $html .= '<img src="';
                    $html .= $url;
                    $html .= '" height="48" width="48" alt="View ';
                    $html .= $c->username;
                    $html .= '\'s images" /></a>';
                } else {
                    $html .= '<a href="';
                    $html .= $link;
                    $html .= '" title="Go to this ';
                    $html .= $c->username;
                    $html .= '\'s profile on flickr" rel="friend nofollow">';
                    $html .= '<img src="';
                    $html .= $alturl;
                    $html .= '" height="48" width="48" alt="View ';
                    $html .= $c->username;
                    $html .= '\'s images" /></a>';
                }
                }
                $contactsurl = $this->view->url(array(
                    'module' => 'flickr',
                    'controller' => 'contacts'
                    ),'default',true);
            $html .= '<p>View our <a href="';
            $html .= $contactsurl;
            $html .= '" title="View our contacts">';
            $html .= $total;
            $html .= '</a> friends and their images &raquo;</p>';
            $this->getCache()->save($html);
        } else {
            $html = $this->getCache()->load($key);
        }
        return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrContactsList
     */
    public function flickrContactsList() {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        $access = $this->getAccessKeys();
        if (!is_null($access)) {
            return $this->getContacts($access);
        } else {
            return 'There has been an error with accessing the Flickr api';
        }
    }
}
