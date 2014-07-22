<?php
/** Controller for administering coroner details
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Admin_CoronersController extends Pas_Controller_Action_Admin {
	
	protected $_coroners;
	/** Set up the ACL and contexts
	*/	
	public function init() 
	{
		$flosActions = array('index');
		$this->_helper->_acl->allow('flos',$flosActions);
		$this->_helper->_acl->allow('fa',null);
		$this->_helper->_acl->allow('admin',null);
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_coroners = new Coroners();
    }
	
	protected $_redirectUrl = 'admin/coroners/';
	
	/** Display index page of coroners
	*/	
	public function indexAction() 
	{
		$this->view->coroners = $this->_coroners->getAll($this->getAllParams());
	}
	
	/** Add a new coroner
	*/		
	public function addAction() 
	{
		$form = new CoronerForm();
		$form->submit->setLabel('Add a new coroner');
		$this->view->form = $form;
		if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
	    	if ($form->isValid($form->getValues())) {
				$this->_coroners->addCoroner($form->getValues());
				$this->_flashMessenger->addMessage('Coroner details created!');
				$this->_redirect($this->_redirectUrl);
			} else {
				$form->populate($form->getValues());
			}
		}
	}
	
	/** Edit a coroner
	*/	
	public function editAction() 
	{
		if($this->_getParam('id',false)) {
			$form = new CoronerForm();
			$form->submit->setLabel('Save');
			$this->view->form = $form;
			if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    			if ($form->isValid($form->getValues())) {
    				$insert = $this->_coroners->updateCoroner($form->getValues(), $this->_getParam('id'));
    				$this->_flashMessenger->addMessage($form->getValue('firstname') . ' ' 
    				. $form->getValue('lastname') . '\'s information updated!');
    				$this->_redirect($this->_redirectUrl);
    			} else {
    				$form->populate($form->getValues());
    			}
			} else {
				// find id is expected in $params['id']
				$id = (int)$this->_request->getParam('id', 0);
				if ($id > 0) {
					$form->populate($this->_coroners->fetchRow('id =' . $id)->toArray());
				}
			}
		} else {
			throw new Pas_Exception_Param($this->_missingParameter, 500);
		}
	}
	
	/** Delete a coroner
	*/		
	public function deleteAction() 
	{
		if ($this->_request->isPost()) {
			$id = (int)$this->_request->getPost('id');
			$del = $this->_request->getPost('del');
			if ($del == 'Yes' && $id > 0) {
				$where = 'id = ' . $id;
				$this->_coroners->delete($where);
			}	
			$this->_flashMessenger->addMessage('Coroner\'s information deleted! This cannot be undone.');
			$this->_redirect($this->_redirectUrl);
		} else {
			$id = (int)$this->_request->getParam('id');
			if ($id > 0) {
				$this->view->coroner = $this->_coroners->fetchRow('id =' . $id);
			}
		}
	}
}