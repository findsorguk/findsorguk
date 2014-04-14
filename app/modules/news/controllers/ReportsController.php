<?php
/** Controller for all reports
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_ReportsController extends Pas_Controller_Action_Admin
{
	protected $_cache = NULL;
	
	protected $_config = NULL;
	
	/** Initialise the ACL and contexts
	*/ 
	public function init(){
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow('public',null);
		$this->_config = Zend_Registry::get('config');
		$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/** Render index pages
	*/ 
	public function indexAction(){
	$content = new Content();
	$slug = $this->_getParam('slug');
	if($slug == '\d+') {
	$this->view->contents = $content->getFrontContent('reports');
	$content = new Content();
	$this->view->contents = $content->getFrontContent('publications');
	$service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
	$client = Zend_Gdata_ClientLogin::getHttpClient($this->_config->webservice->google->username, $this->_config->webservice->google->password, $service);
	$docs = new Zend_Gdata_Docs($client);
	$docsQuery = new Zend_Gdata_Docs_Query();
	$docsQuery->setQuery('title:Scheme Annual Report');
	$feed = $docs->getDocumentListFeed($docsQuery);
	$documents = array();	
	foreach ($feed->entries as $entry) {
	$title = $entry->title;
	foreach ($entry->link as $link) {
    if ($link->getRel() === 'alternate') {
    $altlink = $link->getHref();
    }
	}
    $documents[]=array('title' => $title, 
    'altlink' => $altlink,
    'updated' => $entry->updated,
    'type' => $entry->content->type,
    'published' => $entry->published
    );    
	}
	$this->view->documents = $documents;
	} else {
	$this->view->contents = $content->getContent('reports',$this->_getParam('slug'));
	}
    }
}