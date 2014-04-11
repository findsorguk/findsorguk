<?php
/** A view helper for displaying flickr tags
 * @version 1
 * @since 7 October 2011
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Yql_Oauth
 */
class Pas_View_Helper_FlickrTags
	extends Zend_View_Helper_Abstract {
	
	
	protected $_cache;
	
	protected $_flickr;
	
	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
	}

	
	/** Get flickr data
	 * @param object $flickr
	 */
	protected function getFlickr($flickr){
	if (!($this->_cache->test('cloud'))) {
	$data = $this->_flickr->getTagsListUserPopular($flickr->userid);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('cloud');
	}
	$tags = array();
	foreach($data->tag as $s){
	$tags[] = array('tag' => $s->content,'count' => (int)$s->count);
	}
	return $this->createTagCloud($tags);
	}

	/** Create the html array for redisplay as a tag cloud
	 * @param array $tags
	 * @return string $html
	 */
	public function createTagCloud($tags){
	$tag = array();
	foreach($tags as $tagged){
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
	'htmlTags' => array( 'li'),
	'minFontSize' => 100,
	'maxFontSize' => 200,
	'fontSizeUnit' => '%')));
	$cloud = new Zend_Tag_Cloud($tags);
	return $cloud;
	}
	
	/** Get tags from flickr
	 * @param object $flickr
	 */
	public function flickrTags($flickr) {
	$this->_flickr = new Pas_Yql_Flickr($flickr);
	return $this->getFlickr($flickr);
	}
	
}

