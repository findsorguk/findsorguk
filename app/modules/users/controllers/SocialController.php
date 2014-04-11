<?php
/** Controller for displaying Roman articles within the coin guide
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_SocialController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {	
	$this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Display index pages for the individual
	*/	
	public function indexAction() {	
	$services = new OnlineAccounts();
	$this->view->services = $services->getAllAccounts( (int)$this->getIdentityForForms() );
	}
	/** Add a new account
	*/		
	public function addAction()	{
	$form = new SocialAccountsForm();
	$form->submit->setLabel('Submit profile');

	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$services = new OnlineAccounts();
	
	$insertData = array(
	'accountName' => $form->getValue('accountName'),
	'account' => $form->getValue('account'),
	'public' => $form->getValue('public'),
	'userID' => $this->getIdentityForForms(),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	
	$services->insert($insertData);
	$this->_flashMessenger->addMessage('A new account has been added to your profile.');
	$this->_redirect('/users/');
	} else {
	$form->populate($formData);
	}
	}
	}
	
	/** Edit one of your social media accounts
	*/		
	public function editAction(){
	if($this->_getParam('id',false)) {
	$form = new SocialAccountsForm();
	$form->submit->setLabel('Save profile');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$services = new OnlineAccounts();	
	$updateData = array(
	'accountName' => $form->getValue('accountName'),
	'account' => $form->getValue('account'),
	'public' => $form->getValue('public'),
	'userID' => $this->getIdentityForForms(),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] = $services->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$where[] = $services->getAdapter()->quoteInto('userID = ?',$this->getIdentityForForms());
	$update = $services->update($updateData,$where);
	$this->_flashMessenger->addMessage('Webservice details updated.');
	$this->_redirect('/users/');
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	
	$services = new OnlineAccounts();
	$service = $services->fetchRow('userID = '.$this->getIdentityForForms().' AND id='.$id);
	if(count($service)) {
	$form->populate($service->toArray());
	} else {
	throw new Exception($this->_nothingFound);
	}
	}
	}
	} else {
	throw new Exception($this->_missingParameter);
	}
	}
	/** Delete an account from social media
	*/	
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$services = new OnlineAccounts();
	$where = array();
	$where[] = $services->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$where[] = $services->getAdapter()->quoteInto('userID = ?',$this->getIdentityForForms());
	$services->delete($where);
	}
	$this->_redirect('/users/');
	$this->_flashMessenger->addMessage('Social profile deleted!');
	} else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$services = new OnlineAccounts();
	$this->view->service = $services->fetchRow('id='.$id);
	}
	}
	}	
	
}