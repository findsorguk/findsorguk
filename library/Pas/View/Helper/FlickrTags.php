<?php
<<<<<<< HEAD
/** 
 * A view helper for displaying flickr tags
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->flickrTags()
 * ?>
 * </code>
 * 
 * @version 1
 * @since 7 October 2011
 * @copyright Daniel Pett
=======
/**
 *  A view helper for displaying flickr tags
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->flickrTags()->setApi($flickr);
 * ?>
 * </code>
 *
 * @version 1
 * @since 7 October 2011
 * @copyright Daniel Pett
 * @author Daniel Pett <dpett@britishmuseum.org>
>>>>>>> origin/issue62RefactorViewHelpers
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Oauth
<<<<<<< HEAD
 * @author Daniel Pett <dpett at britishmuseum.org>
 */
class Pas_View_Helper_FlickrTags extends Zend_View_Helper_Abstract {

    /** The cache
     * @access protected
     * @var type 
     */
    protected $_cache;

    /** The api key to access flickr
     * @access protected
     * @var type 
     */
    protected $_apiKey;
    
    protected $_api;
    
    public function getApi() {
        $this->_api = new Pas_Yql_Flickr($this->getApiKey());
        return $this->_api;
    }

        
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    public function getApiKey() {
        return $this->_apiKey;
    }

    public function setCache($cache) {
        $this->_cache = $cache;
        return $this;
    }

    public function setApiKey($apiKey) {
        $this->_apiKey = $apiKey;
        return $this;
    }
    
    protected function getData($flickr)  {
        if (!($this->getCache()->test('cloud'))) {
            $data = $this->getApi()->getTagsListUserPopular($flickr->userid);
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load('cloud');
        }
        $tags = array();
        foreach ($data->tag as $tag) {
            $tags[] = array(
                'tag' => $tag->content,
                'count' => (int) $tag->count
                    );
        }
        return $this->createTagCloud($tags);
=======
 * @example /app/modules/flickr/views/scripts/index/index.phtml
 * @license http://URL name
 */
class Pas_View_Helper_FlickrTags extends Zend_View_Helper_Abstract {

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The api
     * @access protected
     * @var string
     */
    protected $_api;

    /** Get the apikey
     * @access public
     * @return type
     */
    public function getApi() {
        return $this->_api;
    }

    /** Set the api up
     * @access public
     * @param string $api
     * @return \Pas_View_Helper_FlickrTags
     */
    public function setApi( $api) {
        $this->_api = new Pas_Yql_Flickr($api);
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

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FlickrTags
     */
    public function flickrTags() {
        return $this;
>>>>>>> origin/issue62RefactorViewHelpers
    }

    /** To string function
     * @access public
     * @return string
     */
<<<<<<< HEAD
    public function createTagCloud( array $tags) {
        $tag = array();
        foreach ($tags as $tagged) {
            $tag[] = array(
                'title' => strtolower($tagged['tag']),
                'weight' => $tagged['count'],
                'params' => array(
                    'url' => $this->view->url(array(
                        'module' => 'flickr',
                        'controller' => 'photos',
                        'action' => 'tagged',
                        'as' => strtolower($tagged['tag'])),
                            'default',
                            true)));
            }
            $tags = array(
                'tags' => $tag,
                'cloudDecorator' => array(
                    'decorator' => 'HtmlCloud',
                    'options' => array('htmlTags' => array(
                        'ul' => array('id' => 'period-object-cloud')))),
                'tagDecorator' => array(
                    'decorator' => 'HtmlTag',
                    'options' => array(
                        'htmlTags' => array('li'),
                        'minFontSize' => 100,
                        'maxFontSize' => 200,
                        'fontSizeUnit' => '%')));
        $cloud = new Zend_Tag_Cloud($tags);
        return $cloud;
    }

    public function flickrTags() {
        return $this;
    }

    public function __toString() {
        $this->_flickr = new Pas_Yql_Flickr($flickr);
        return $this->getFlickr($flickr);
=======
    public function __toString() {
        return $this->getFlickr($this->getApi());
    }

    /** Get the flickr results
     * @access public
     * @param type $flickr
     * @return type
     */
    public function getFlickr($flickr) {
        if (!($this->getCache()->test('cloud'))) {
            $data = $this->getApi()->getTagsListUserPopular($flickr->userid);
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load('cloud');
        }
        $tags = array();
        foreach ($data->tag as $s) {
            $tags[] = array(
                'tag' => $s->content,
                'count' => (int) $s->count
                    );
        }
        return $this->createTagCloud($tags);
    }

    /** Create the html array for redisplay as a tag cloud
     * @param  array $tags
     * @return string $html
     */
    public function createTagCloud( array $tags) {
        $tag = array();
        foreach ($tags as $tagged) {
        $tag[] = array(
        'title' => strtolower($tagged['tag']),
        'weight' => $tagged['count'],
        'params' => array(

            'url' => $this->view->url(array(
                'module' => 'flickr',
                'controller' => 'photos',
                'action' => 'tagged',
                'as' => strtolower($tagged['tag'])),
                    'default',
                    true)));
        }
        $tags = array(
            'tags' => $tag,
            'cloudDecorator' => array(
                'decorator' => 'HtmlCloud',
                'options' => array(
                    'htmlTags' => array(
                        'ul' => array(
                            'id' => 'period-object-cloud'
                            )
                        )
                    )
                ),
            'tagDecorator' => array(
                'decorator' => 'HtmlTag',
                'options' => array(
                    'htmlTags' => array( 'li'),
                    'minFontSize' => 100,
                    'maxFontSize' => 200,
                    'fontSizeUnit' => '%')));
        $cloud = new Zend_Tag_Cloud($tags);
        return $cloud;
>>>>>>> origin/issue62RefactorViewHelpers
    }
}
