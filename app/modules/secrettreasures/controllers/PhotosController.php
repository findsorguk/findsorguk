<?php
/** Controller for displaying the photos section of the flickr module.
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Secrettreasures_PhotosController
	extends Pas_Controller_Action_Admin {

	protected $_oauth, $_config, $_userid, $_cache, $_api;
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_config = Zend_Registry::get('config');
	$this->_flickr = $this->_config->webservice->flickr;
	$this->_cache = Zend_Registry::get('flickrcache');
	$this->_api	= new Pas_Yql_Flickr($this->_flickr);
	}

	/** No direct access to photos, goes to the index controller
	*/
	public function indexAction() {
	$this->_flashMessenger->addMessage('You can only see photos at the index page');
	$this->_redirect('/secrettreasures/');
        }

        /** Retrieve the page number
        *
        */
        public function getPage(){
        $page = $this->_getParam('page');
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	return $start;
        }

        /** Retrieve the sets of photos we have
	*/
	public function setsAction() {
	$page = $this->getPage();
	$key = md5('sets' . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getSetsList($this->_flickr->userid, $page,10);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$pagination = array(
	'page'          => $page,
	'perpage'      => (int)$flickr->photosets->perpage,
        'total_results' => (int)$flickr->photosets->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
        $paginator->setCurrentPageNumber($pagination['page'])
		->setItemCountPerPage($pagination['perpage'])
		->setCache($this->_cache);
	$this->view->paginator = $paginator;
	$this->view->photos = $flickr->photosets;
	}

	/** Find photos with a set radius of the where on earthID
	*/
	public function whereonearthAction() {
	$woeid = (int)$this->_getParam('id');
	$page = $this->getPage();
	$this->view->place = $woeid;
	$key = md5('woeid' . $woeid . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getWoeidRadius( $woeid, $radius = 500, $units = 'm',
	$per_page = 20, $page, 'archaeology', '1,2,3,4,5,6,7');
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$total = $flickr->photos->total;
	$perpage = $flickr->photos->perpage;
	$pagination = array(
	'page'          => $page,
	'per_page'      => $perpage,
        'total_results' => (int)$total
	);

	$paginator = Zend_Paginator::factory($pagination['total_results']);

	$paginator->setCurrentPageNumber($pagination['page'])
                ->setItemCountPerPage(20)
		->setPageRange(10)
                ->setCache($this->_cache);
	$this->view->paginator = $paginator;
	$this->view->pictures = $flickr;

	}
	/** Find images in a set
	*/
	public function inasetAction() {
	if($this->_getParam('id',false)){
	$id = $this->_getParam('id');
	$page = $this->getPage();
	$key = md5 ('set' . $id . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getPhotosInAset($id, 10, $page);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$pagination = array(
	'page'          => $page,
	'per_page'      => $flickr->photoset->perpage,
        'total_results' => (int)$flickr->photoset->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
	$paginator->setCurrentPageNumber($pagination['page'])
		->setItemCountPerPage(10)
		->setCache($this->_cache);
	$paginator->setPageRange(10);
	$this->view->paginator = $paginator;
	$this->view->pictures = $flickr;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** get photos's details
	*/
	public function detailsAction() {
	if($this->_getParam('id',false)){
	$id = $this->_getParam('id');
	$exif = $this->_api->getPhotoExifDetails( $id );
	$this->view->exif = $exif;
	$geo = $this->_api->getGeoLocation($id);
	$this->view->geo = $geo;
	$comments = $this->_api->getPhotoCommentList($id);
	$this->view->comments = $comments;
	$image = $this->_api->getPhotoInfo($id);
	$this->view->image = $image;
	$sizes = $this->_api->getSizes($id);
	$this->view->sizes = $sizes;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter, 500);
	}
	}

	/** Find images tagged in a certain way.
	*/
	public function taggedAction() {
	if($this->_getParam('as',false)){
	$tags = $this->_getParam('as');
	$page = $this->getPage();
	$key = md5('tagged' . $tags . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getPhotosTaggedAs( $tags, 20, $page);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$photos = array();
	if(!is_null($flickr)){
	$total = $flickr->total;
	$photos = array();
	foreach($flickr->photo as $k => $v) {

	$photos[$k] = $v;
	}

	$this->view->tagtitle = $tags;
	$pagination = array(
	'page'          => $page,
	'results' 		=> $photos,
	'per_page'      => 20,
        'total_results' => (int) $total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
	$paginator->setCurrentPageNumber($pagination['page']) ;
	$paginator->setPageRange(10);
	$paginator->setItemCountPerPage(20);
	$this->view->paginator = $paginator;
	$this->view->pictures = $photos;
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	/** Get a list of our favourite images
	*/
	public function favouritesAction() {
	$page = $this->getPage();
	$key = md5('faves' . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getPublicFavourites( NULL, NULL, 20, $page);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$pagination = array(
	'page'          => $page,
	'per_page'      => (int)$flickr->perpage,
        'total_results' => (int)$flickr->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
	$paginator->setCurrentPageNumber($page)
		->setPageRange(10)
		->setCache($this->_cache);
	$this->view->paginator = $paginator;
	$this->view->photos = $flickr;
	}

	/** Get a list of interesting flickr images attributed to archaeology
	 * The woeid 23424975 = United Kingdom
	 *
	*/
	public function interestingAction() {
	$page = $this->getPage();
	$key = md5('interesting' . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getArchaeology( 'archaeology', 20, $page, 23424975);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$pagination = array(
	'page'          => $page,
        'total_results' => (int)$flickr->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
	$paginator->setCurrentPageNumber($page)
		->setPageRange(10)
		->setCache($this->_cache);
	$paginator->setItemCountPerPage(20);
	$this->view->paginator = $paginator;
	$this->view->photos = $flickr;
	}
}