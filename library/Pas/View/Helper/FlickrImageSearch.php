<?php
/**
 * FlickrImageSearch helper
 *
 * A view helper for rendering images from the flickr api as a search.
 * An example of use:
 * <code>
 * <?php
 * echo $this->flickrImageSearch()
 * ->setTerm($term)
 * ->setLimit($limit)
 * ->setApi($apiKey);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @license
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 * @example /app/modules/experiments/views/scripts/middleeast/person.phtml
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_FlickrImageSearch extends Zend_View_Helper_Abstract
{
    /** Get the cache
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;
    
    /** The default limit
     * @access protected
     * @var int
     */
    protected $_limit = 4;
    
    /** Get the limit
     * @access public
     * @return int
     */
    public function getLimit() {
        return $this->_limit;
    }

    /** Set the limit
     * @access public
     * @param int $limit
     * @return \Pas_View_Helper_FlickrImageSearch
     */
    public function setLimit($limit) {
        $this->_limit = $limit;
        return $this;
    }
        
    /** Get the api
     * @access protected
     * @var \Pas_Yql_Flickr
     */
    protected $_api;

    /** The term to search
     * @access protected
     * @var string
     */
    protected $_term;
    
    /** Get the term to search
     * @access public
     * @return string
     */
    public function getTerm() {
        return $this->_term;
    }

    /** Set the term to search on
     * @access public
     * @param string $term
     * @return \Pas_View_Helper_FlickrImageSearch
     */
    public function setTerm($term) {
        $this->_term = $term;
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

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrImageSearch
     */
    public function flickrImageSearch() {
        return $this;
    }
    
    /** Search the api
     * @access public
     * @param string $term
     * @param int $limit
     * @return string
     */
    public function searchApi( $term, $limit  ) {
        if (isset($term)) {
        $photos = $this->getApi()->searchPhotos($term, $limit);
        if (!is_array($photos->photo)) {
            $photos->photo = array($photos->photo);
        }
        $html = '<div class="row-fluid">';
        if ($photos->photo) {
            $html .= '<h2>Flickr images via YQL</h2>';
            $html .= '<h3 class="lead">Photos associated with ';
            $html .= $term;
            $html .= '</h3>';
        if (is_array($photos->photo)) {
            $html .= $this->view->partialLoop('partials/flickr/favourite.phtml', 
                    $photos->photo);
        } else {
            $html .= $this->view->partial('partials/flickr/favourite.phtml', 
                    $photos->photo);
        }
        $html .= '</div>';
        }
        } 
        return $html;
    }
    
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->searchApi($this->getTerm(), $this->getLimit());
    }
}