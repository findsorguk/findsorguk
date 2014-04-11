<?php
/** Controller for adding and manipulating quotes
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_QuotesController extends Pas_Controller_Action_Admin {

	protected $_quotes;
	
	const REDIRECT = '/admin/quotes/';
	/** Set up the ACL and contexts
	*/			
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_quotes = new Quotes();
	}
	/** List all the quotes
	*/		
	public function indexAction() {
	$this->view->quotes = $this->_quotes->getQuotesAdmin($this->_getParam('page'));
	}
	/** Add a new quote
	*/		
	public function addAction() {
	$form = new QuoteForm();
	$form->details->setLegend('Add a new quote or announcement');
	$form->submit->setLabel('Submit details');
	$this->view->form =$form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$insert = $this->_quotes->add($form->getValues());
	$this->_flashMessenger->addMessage('New quote/announcement entered');
	$this->_redirect( self::REDIRECT );
	} else  {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a quote
	*/			
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new QuoteForm();
	$form->details->setLegend('Edit quote/announcement details');
	$form->submit->setLabel('Submit changes');
	$this->view->form =$form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] = $this->_quotes->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_quotes->update($form->getValues(),$where);
	$this->_flashMessenger->addMessage('Details updated!');
	$this->_redirect( self::REDIRECT );
	} else {
	$form->populate($form->getValues());
	}
	} else {
	$form->populate($this->_quotes->fetchRow('id=' 
	. $this->_request->getParam('id'))->toArray());
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	/** Delete a quote
	*/		
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_quotes->delete($where);
	$this->_flashMessenger->addMessage('Quote/announcement deleted!');
	}
	$this->_redirect( self::REDIRECT);
	}  else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->quote = $this->_quotes->fetchRow('id =' . $id);
	}
	}
	}

}