<?php
/**
 * A view helper for getting the NSID from a flickr username
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Pas_Yql_Flickr
 */
class Pas_View_Helper_FlickrNsid
	extends Zend_View_Helper_Abstract{

	protected $_cache;
	protected $_config;
	protected $_api;

	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_cache = Zend_Registry::get('cache');
	$this->_api = new Pas_Yql_Flickr($this->_config->webservice->flickr);
	}

	/** Get the flickr nsid
	 * @param string $username
	 * @return string $flickr
	 */
	public function flickrNsid( $username ) {
	if(!is_null($username)){
	if (!($this->_cache->test(md5($username)))) {
	$flickr = $this->_api->findByUsername($username);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load(md5($username));
	}
	return $flickr;
		}
	}
}

