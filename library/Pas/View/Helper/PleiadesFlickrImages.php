<?php
/**
 * Pleiades Flickr Images view helper
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @uses viewHelper Pas_View_Helper
 * @version 1
 * @uses Zend_Cache
 * @uses Zend_Registry
 * @uses Pas_Yql_Flickr
 * @category Pas
 * @package Pas_View_Helper
 */
class Pas_View_Helper_PleiadesFlickrImages extends Zend_View_Helper_Abstract {
    
    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The api end point
     * @access protected
     * @var \Pas_Yql_Flickr 
     */
    protected $_api;

    /** The flickr api key
     * @access protected
     * @var string
     */
    protected $_flickr;

    /** The Zend config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;

    /** The constructor
     * 
     */
    public function __construct() {
        $this->_config = Zend_Registry::get('config');
        $this->_cache = Zend_Registry::get('cache');
        $this->_flickr = $this->_config->webservice->flickr;
        $this->_api	= new Pas_Yql_Flickr($this->_flickr);
    }

    /** The function
     * @access public
     * @param integer $pleiadesID
     * @return string
     */
    public function pleiadesFlickrImages($pleiadesID) {
        $html = '';
        if (isset($pleiadesID)) {
            $photos = $this->_api->getMachineTagged('pleiades:depicts=' . $pleiadesID, 5);
            if (array_key_exists('photo', $photos)) {
                if(is_object($photos->photo)) {
                    $photos = array($photos->photo);
                } else {
                    $photos = $photos->photo;
                }
                if (is_array($photos)) {
                    $html .= '<div class="row-fluid"><h3 class="lead">Photos linked to this Pleiades ID</h3>';
                    $html .= $this->view->partialLoop('partials/flickr/mints.phtml', $photos);
                    $html .= '</div>';
                    return $html;
                }
            }
        } 
        return $html;
    }
}