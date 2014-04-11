<?php
/** Controller for administering vacancies 
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_VacanciesController extends Pas_Controller_Action_Admin {
	
	protected $_vacancies;
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_vacancies = new Vacancies();
	}
	/** 
	 * 
	 * @staticvar string Redirect url
	 */
	const REDIRECT = '/admin/vacancies';
	/** Setup the contexts by action and the ACL.
	*/	
	public function indexAction() {
	$this->view->currentvacs = $this->_vacancies->getJobsAdmin($this->_getParam('page'));
	}
	/** Add a new vacancy
	*/	
	public function addAction() {
	$form = new VacancyForm();
	$form->submit->setLabel('Add a new vacancy');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$this->_vacancies->add($form->getValues());
	$this->_flashMessenger->addMessage('Vacancy details created: ' . $form->getValue('title'));
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
	$form = new VacancyForm();
	$form->submit->setLabel('Update details');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] = $this->_vacancies->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_vacancies->update($form->getValues(), $where);
	$this->_flashMessenger->addMessage('Vacancy details updated!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_getParam('id', 0);
	if ($id > 0) {
	$vac = $this->_vacancies->fetchRow('id = ' . $id);
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
	$this->_vacancies->delete($where);
	$this->_flashMessenger->addMessage('Record deleted');
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->vac = $this->_vacancies->fetchRow('id = ' . $id);
	}
	}
	}


}