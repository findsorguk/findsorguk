<?php
/** A view helper for displaying flickr total views for photos for your account.
 * This only works if you have a flickr pro license.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->flickrTotalViews()
 * ->setApi($apikey);
 * ?>
 * </code>
 * 
 * @version 1
 * @since 25 October 2011
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Flickr
 * @uses Zend_Cache
 * @uses Zend_Registry
 * @license 
 * @example /app/modules/flickr/views/scripts/index/index.phtml
 */
class Pas_View_Helper_FlickrTotalViews {
    
    /** The api for accessing flickr
     * @access protected
     * @var \Pas_Yql_Flickr
     */
    protected $_api;
    
    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;
    
    /** Get the cache
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }
    
    /** Get the flickr api wrapper
     * @access public
     * @return \Pas_Yql_Flickr
     */
    public function getApi() {
        return $this->_api;
    }

    /** Set the api key
     * @access public
     * @param string $apiKey
     * @return \Pas_View_Helper_FlickrTotalViews
     */
    public function setApi($apiKey) {
        $this->_api = new Pas_Yql_Flickr($apiKey);
        return $this;
    }

    /** Get the number of views
     * @access public
     * @return array
     */
    public function getViews() {
        if (!$flickr = $this->getCache()->load('flickrviews')) {
            $flickr = $this->getApi()->getTotalViews();
            $this->getCache()->save($flickr, 'flickrviews');
        }
        return $this->buildHtml($flickr);
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrTotalViews
     */
    public function flickrTotalViews() {
        return $this;
    }

    /** Build the html
     * @access public
     * @param array $flickr
     * @return string
     */
    public function buildHtml(array$flickr) {
        $html = '<h3>Photo Statistics</h3>';
        if (array_key_exists('stats' , $flickr)) {
            $html .= '<p>';
            $stats = array();
            foreach ($flickr->stats as $k => $v) {
                if ($v->views > 0) {
                    $stats[] = ucfirst($k) . ' views: '. number_format($v->views);
                }
                }
                $html .= implode(' | ', $stats);
                $html .= '</p>';
            } else {
                $html .= $flickr->message;
            }
         return $html;
    }
    
    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getViews());
    }
}