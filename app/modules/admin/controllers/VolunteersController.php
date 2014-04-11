<?php
/** Controller for administering vacancies 
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_VolunteersController extends Pas_Controller_Action_Admin {
	
	protected $_volunteers;
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_volunteers = new Volunteers();
	}
	/** 
	 * 
	 * @staticvar string Redirect url
	 */
	const REDIRECT = '/admin/volunteers';
	/** Setup the contexts by action and the ACL.
	*/	
	public function indexAction() {
	$this->view->opps = $this->_volunteers->getCurrentOpps($this->_getParam('page'));
	}
	/** Add a new vacancy
	*/	
	public function addAction() {
	$form = new VolunteerForm();
	$form->submit->setLabel('Add new role');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$this->_volunteers->add($form->getValues());
	$this->_flashMessenger->addMessage('Volunteer role details created: ' . $form->getValue('title'));
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a vacancy
	*/		
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new VolunteerForm();
	$form->submit->setLabel('Update details');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] = $this->_volunteers->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_volunteers->update($form->getValues(), $where);
	$this->_flashMessenger->addMessage('Vacancy details updated!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_getParam('id', 0);
	if ($id > 0) {
	$vac = $this->_volunteers->fetchRow('id = ' . $id);
	if(count($vac)) {
	$form->populate($vac->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParamter);
	}
	}
	/** Delete a vacancy
	*/	
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_getParam('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . (int)$id;
	$this->_volunteers->delete($where);
	$this->_flashMessenger->addMessage('Record deleted');
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->opp = $this->_volunteers->fetchRow('id = ' . $id);
	}
	}
	}


}