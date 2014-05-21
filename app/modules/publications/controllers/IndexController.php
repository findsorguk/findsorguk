<?php 
/** Controller for manipulating publications data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Move adding data and editing into model
*/
class Getinvolved_PublicationsController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL, cache and config
	*/ 
    public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow(null);
    }
	
    /** Render documents on the index page
	*/ 
	public function indexAction() {
	$content = new Content();
	$this->view->contents = $content->getFrontContent('publications');
	$service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
	$client = Zend_Gdata_ClientLogin::getHttpClient($this->_helper->Config()->webservice->google->username, 
	$this->_helper->Config()->webservice->google->password, $service);
	$docs = new Zend_Gdata_Docs($client);
	$feed = $docs->getDocumentListFeed();
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
	}
	
	/** Handle the requests for publications
	*/ 
	public function requestAction() {
	$form = new RequestForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $data = array_filter($form->getValues());
	$cc = array();
	$cc[] = array('email' => $form->getvalue('email'),'name' => $form->getValue('fullname'));
	$this->_helper->mailer($data, 'requestPublication', null, $cc, $cc );
	$this->_flashMessenger->addMessage('Your request has been submitted');
	$this->_redirect('getinvolved/publications/');
	} else {
	$this->_flashMessenger->addMessage('There are problems with your submission');
	$form->populate($form->getValues());
	}
	}
	}

}