<?php
/** Controller for pulling ajax data from flickr.
* 
* @category   Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license   	GNU General Public License
* @author	 	Daniel Pett
* @version	 	1
* @since	 	10 October 2011
* @uses		 	Pas_Yql_Flickr
* @uses		 	Pas_Yql_Exception
* @uses			Zend_Paginator
*/

class Flickr_ContactsController extends Pas_Controller_Action_Admin{

	/** Flickr config
	 * 
	 * @var object
	 */
	protected $_flickr;
	
	/** The flickr api
	 * 
	 * @var object
	 */
	protected $_api;
	
	protected $_cache;
	
	/**Initiate the api and config, ACl
	 * 
	 */
	public function init(){
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_flickr = Zend_Registry::get('config')->webservice->flickr;
	$this->_api	= new Pas_Yql_Flickr($this->_flickr);
	$this->_cache = Zend_Registry::get('cache');
	}
	
	/** Display the index page
	*/			
	public function indexAction() {
	$page = $this->_getParam('page');
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page;
	}
	$contacts = $this->_api->getContacts($start);
	$pagination = array(    
	'page'          => $page, 
	'contacts' 		=> $contacts->contact,
	'per_page'      => (int)$contacts->per_page, 
    'total_results' => (int)$contacts->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
		->setItemCountPerPage($pagination['per_page'])
		->setCache($this->_cache);
	$this->view->paginator = $paginator;
	$this->view->contacts = $contacts;
	}
	
	/** Display contact details and some images
	* @throws Pas_Yql_Exception
	*/
	public function knownAction() {
	if($this->_getParam('as',false)){
	$this->view->details	= $this->_api->getContactDetails($this->_getParam('as'));
	
	if (!($this->_cache->test(md5('contacts'.$this->_getParam('as'))))) {
	$ph	= $this->_api->getContactPhotos($this->_getParam('as'), 0, 18);
	$this->_cache->save($ph);
	} else {
	$ph = $this->_cache->load(md5('contacts'.$this->_getParam('as')));
	}
	$this->view->photos = $ph;
	} else {
		throw new Pas_Yql_Exception($this->_missingParameter);
	}
	}

}

