<?php
/**
 * A view helper for displaying info about a flickr set
 * Could be abstracted to a flickr class
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->flickrSetInfo()->setApiKey($flickr)->setSetId(1);
 * ?>
 * </code>
 *
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
 * @example /app/modules/flickr/views/scripts/photos/inaset.phtml
 */
class Pas_View_Helper_FlickrSetInfo extends Zend_View_Helper_Abstract {

    /** The cache
     * @access public
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The yql class
     * @access protected
     * @var object
     */
    protected $_oauth;

    /** The api key
     * @access protected
     * @var string
     */
    protected $_apiKey;

    /** The set id
     * @access protected
     * @var int
     */
    protected $_setId;

    /** Get the api key
     * @access public
     * @return string
     */
    public function getApiKey() {
        return $this->_apiKey;
    }

    /** Set the api key
     * @access public
     * @param string $apiKey
     * @return \Pas_View_Helper_FlickrSetInfo
     */
    public function setApiKey($apiKey) {
        $this->_apiKey = $apiKey;
        return $this;
    }

    /** Get the set id to query
     * @access public
     * @return int
     */
    public function getSetId() {
        return $this->_setId;
    }

    /** Set the id to query
     * @access public
     * @param type $setId
     * @return \Pas_View_Helper_FlickrSetInfo
     */
    public function setSetId($setId) {
        $this->_setId = $setId;
        return $this;
    }

    /** Get the cache object
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('rulercache');
        return $this->_cache;
    }

    /** Get the YQL oauth
     * @access public
     * @return object
     */
    public function getOauth() {
        $this->_oauth = new Pas_Yql_Oauth();
        return $this->_oauth;
    }

    /** Get access tokens
     * @access public
     * @return object
     */
    public function getTokens() {
        $tokens = new OauthTokens();
        $where = array();
        $where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess');
        return $tokens->fetchRow($where);
    }

    public function flickrSetInfo() {
        return $this;
    }

    /** Get the set data info
     * @access public
     * @param int $setId
     * @return string
     */
    public function getSetData( $setId ){
        if (!($this->getCache()->test('flickrSet' . $setId))) {
            $query = 'SELECT * FROM flickr.photosets.info WHERE photoset_id="';
            $query .= $setId;
            $query .= '" and api_key="';
            $query .= $this->getApiKey();
            $query .= '";';
            $token = $this->getTokens();
            $flickr = $this->getOauth()->execute(
                    $query,
                    unserialize($token->accessToken),
                    unserialize($token->tokenSecret),
                    $token->expires,
                    unserialize($token->sessionHandle)
                    );
            $this->getCache->save($flickr);
        } else {
            $flickr = $this->getCache()->load('flickrSet' . $setId);
        }
        $title = 'All photos in the set titled: ' . $flickr->query->results->photoset->title;
        $this->view->headTitle($title);
        $this->view->MetaBase(
                $flickr->query->results->photoset->description,
                'photos',
                'archaeology, photos, portable antiquities '
                );
        return '<h2>' . $flickr->query->results->photoset->title . '</h2>';
    }

    /** To string function
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->getSetData( $this->getSetId() );
    }

}
