<?php 
/** Controller for displaying information about hoards
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_HoardsController extends Pas_Controller_Action_Admin {
	
	protected $_hoards;
	
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$publicActions = array('index','hoard');
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('public',$publicActions);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('hoard', array('xml','json'))
		->addActionContext('index', array('xml','json','rss','atom'))
		->initContext();
	$this->_hoards = new Hoards();
	}
	
	/** Url redirect 
	*/
	const REDIRECT = 'database/hoards/';
    
    /** Index page, listing all hoards recorded on the database.
	*/
	public function indexAction() {
	$this->view->hoards = $this->_hoards->getHoardList((array)$this->_getAllParams());
	}
	
    /** Details of an individual hoard
	* @throws Exception if parameter missing 
	*/
	public function hoardAction() {
	if($this->_getParam('id',false)){
	$this->view->hoards = $this->_hoards->getHoardDetails((int)$this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
    /** Insert a new hoard cover page
	*/	
	public function addAction() {
	$form = new HoardForm();
	$form->submit->setLabel('Add a new hoard...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = array();
	$data['term'] = $form->getValue('term');
	$data['termdesc'] = $form->getValue('termdesc');
	$data['period'] = $form->getValue('period');
	$hoards = new Hoards();
	$insert = $hoards->insert($data);
	$this->_redirect(self::REDIRECT . '/hoard/id/' . $insert);
	$this->_flashMessenger->addMessage('A new hoard has been created on the system!');
	} else {
	$this->_flashMessenger->addMessage($this->_formErrors);
	$form->populate($formData);
	}
	}
	}
	
	/** Edit a hoard's coverpage details 
	*/
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new HoardForm();
	$form->submit->setLabel('Update details...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$hoards = new Hoards();
	$data = array();
	$data['term'] = $form->getValue('term');
	$data['termdesc'] = $form->getValue('termdesc');
	$data['period'] = $form->getValue('period');
	$where = array();
	$where[] = $hoards->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $hoards->update($data,$where); 
	$this->_flashMessenger->addMessage('Hoard information updated!');
	$this->_redirect(self::REDIRECT . 'hoard/id/' . $this->_getParam('id'));
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$hoards = new Hoards();
	$hoard = $hoards->fetchRow('id=' . $id);
	if(count($hoards)) {
	$form->populate($hoard->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	/** Delete a particular hoard
	*/	
	public function deleteAction() {
	if($this->_getParam('id',false)) {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$hoards = new Hoards();
	$where = 'id = ' . $id;
	$hoards->delete($where);
	}
	$this->_flashMessenger->addMessage('Record for rally deleted!');
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$hoards = new Hoards();
	$this->view->hoard = $hoards->fetchRow('id=' . $id);
	}
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
}