<?php
/**
 * A view helper for getting the NSID from a flickr username
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->flickrNsid()
 * ->setApi($flickrApiKey)
 * ->setUsername($username);
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Pas_Yql_Flickr
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Zend_Cache
 * @example /app/views/scripts/partials/flickr/set.phtml
 */
class Pas_View_Helper_FlickrNsid extends Zend_View_Helper_Abstract{

    /** Get the cache
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;
    
    /** Get the api
     * @access protected
     * @var \Pas_Yql_Flickr
     */
    protected $_api;
    
    /** The username
     * @access protected
     * @var type 
     */
    protected $_username;
    
    /** Get the username
     * @access public
     * @return string
     */
    public function getUsername() {
        return $this->_username;
    }

    /** Set the username
     * @access public
     * @param string $username
     * @return \Pas_View_Helper_FlickrNsid
     */
    public function setUsername($username) {
        $this->_username = $username;
        return $this;
    }

    /** Get the cache
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the flickr api
     * @access public
     * @return object
     */
    public function getApi() {
        return $this->_api;
    }
    
    /** Set the api
     * @access public
     * @param string $api
     * @return \Pas_View_Helper_FlickrNsid
     */
    public function setApi($apiKey) {
        $this->_api = new Pas_Yql_Flickr($apiKey);
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getNsId( $this->getUsername());
    }

    /** Get the nsid for a username
     * @access public
     * @param string $username
     * @return string
     */
    public function getNsId($username) {
        if (!is_null($username)) {
        if (!($this->getCache()->test(md5($username)))) {
            $flickr = $this->getApi()->findByUsername($username);
            $this->getCache()->save($flickr);
            } else {
                $flickr = $this->getCache()->load(md5($username));
            }
        } else {
            $flickr = 'There has been a problem accessing the api';
        }
        return $flickr;
    }
    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrNsid
     */
    public function getFlickrNsid() {
        return $this;
    }
}