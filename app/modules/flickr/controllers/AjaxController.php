<?php
/** Controller for pulling ajax data from flickr.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Flickr_AjaxController extends Pas_Controller_Action_Admin {

	protected $_cache, $_flickr, $_api;
	/** Setup the contexts by action and the ACL.
	*/			
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow('public',null);
		$this->_helper->layout->disableLayout();  
		$this->_cache = Zend_Registry::get('cache');
		$this->_flickr = Zend_Registry::get('config')->webservice->flickr;
		$this->_api	= new Pas_Yql_Flickr($this->_flickr);
	}
		
	/** Display the index action for mapping flickr images
	*/		
	public function indexAction() {
	if (!($this->_cache->test('mappingflickr'))) { 
	$ph = $this->_api->getPhotosGeoData( $start = 0, $limit = 50, $this->_flickr->userid);
	$this->_cache->save($ph);
	} else {
	$ph = $this->_cache->load('mappingflickr');
	}
	$this->view->recent = $ph;
	$this->_response->setHeader('Content-Type','application/vnd.google-earth.kml+xml');
	}
	
}