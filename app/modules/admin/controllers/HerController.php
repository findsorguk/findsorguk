<?php
/** Controller for setting up and manipulating historic environment data sign ups
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_HerController extends Pas_Controller_Action_Admin {
	
	protected $_hers;
	/** Set up the ACL and contexts
	*/			
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_hers = new Hers();
	}
	/** Set up the index action
	*/		
	public function indexAction() {
	$this->view->hers = $this->_hers->getAll($this->_getAllParams());
	}
	/** Add a signatory
	*/		
	public function addAction() {
	$form = new HerForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$this->_hers->add($form->getValues());
	$this->_flashMessenger->addMessage('A new HER signatory has been created.');
	$this->_redirect('/admin/her/');
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a signatory
	*/			
	public function editAction() {
	$form = new HerForm();
	$form->submit->setLabel('Submit HER details change');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] =  $this->_hers->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_hers->update($form->getValues(),$where);
	$this->_flashMessenger->addMessage($form->getValue('name') . '\'s details updated.');
	$this->_redirect('/admin/her/');
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$form->populate($this->_hers->fetchRow('id=' . $id)->toArray());
	}
	}
	}
	
}
