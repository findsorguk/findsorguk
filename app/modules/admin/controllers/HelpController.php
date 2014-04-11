<?php
/** Controller for setting up and manipulating help topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_HelpController extends Pas_Controller_Action_Admin {
	
	protected $_help;
	/** Set up the ACL and contexts
	*/				
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_help = new Help();
	}
	/** Set up the index of help topics
	*/			
	public function indexAction() {
	$this->view->contents = $this->_help->getContentAdmin($this->_getParam('page'));
	}
	/** Add a new help topic
	*/				
	public function addAction() {
	$form = new HelpForm();
	$form->submit->setLabel('Add new help topic to system');
	$form->author->setValue($this->getIdentityForForms());
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$this->_help->add($form->getValues());
	$this->_flashMessenger->addMessage('Help topic has been created!');
	$this->_redirect('/admin/help');
	} else  {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a help topic
	*/				
	public function editAction(){
	if($this->_getParam('id',false)){
	$form = new HelpForm();
	$form->submit->setLabel('Submit changes');
	$form->author->setValue($this->getIdentityForForms());
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] = $this->_help->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_help->update($form->getValues(),$where);
	$this->_flashMessenger->addMessage('You updated: <em>' . $form->getValue('title')
	. '</em> successfully. It is now available for use.');
	$this->_redirect('admin/help/');
	} else {
	$form->populate($form->getValues());
	}
	} else {
	$form->populate($this->_help->fetchRow('id= '.$this->_getParam('id'))->toArray());
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a help topic
	*/					
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_help->delete($where);
	$this->_flashMessenger->addMessage('Record deleted!');
	}
	$this->_redirect('/admin/help/');
	} else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->content = $this->_help->fetchRow('id=' . $id);
	}
	}
	}
	
	}