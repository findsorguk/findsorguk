<?php
/** Controller for adding and manipulating user roles
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_RolesController extends Pas_Controller_Action_Admin {

	protected $_staffroles;

	protected $_redirectUrl = 'admin/roles/';
	/** Set up the ACL and contexts
	*/
	public function init() {
 	$this->_helper->_acl->allow('flos',array('index'));
	$this->_helper->_acl->allow('fa',null);
 	$this->_helper->_acl->allow('admin',null);
 	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
 	$this->_staffroles = new StaffRoles();
    }
	/** Display the index page
	*/
	public function indexAction() {
	$this->view->roles = $this->_staffroles->getValidRoles();
	}
	/** View a role's details
	*/
	public function roleAction(){
	$this->view->roles = $this->_staffroles->getRole($this->_getParam('id'));
	$this->view->members = $this->_staffroles->getMembers($this->_getParam('id'));
	}
	/** Add a role
	*/
	public function addAction(){
	$form = new StaffRoleForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
	if ($form->isValid($form->getValues())) {
	$this->_staffroles->add($form->getValues());
	$this->_flashMessenger->addMessage('A new staff role has been created.');
	$this->_redirect($this->_redirectUrl );
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a role
	*/
	public function editAction() {
	$form = new StaffRoleForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
        if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] = $this->_staffroles->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_staffroles->update($form->getValues(),$where);
	$this->_flashMessenger->addMessage($form->getValue('role') . '\'s details updated.');
	$this->_redirect($this->_redirectUrl );
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$form->populate($this->_staffroles->fetchRow('id=' . $this->_getParam('id'))->toArray());
	}
	}
	/** Delete a role
	*/
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_staffroles->delete($where);
	}
	$this->_flashMessenger->addMessage('Role information deleted! This cannot be undone.');
	$this->_redirect($this->_redirectUrl);
	} else {
	if ($id > 0) {
	$this->view->role = $this->_staffroles->fetchRow('id =' . $this->_request->getParam('id'));
	}
	}
	}
}
