<?php
/** Controller for manipulating acronyms on the system
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_AcronymsController extends Pas_Controller_Action_Admin {
	
    protected $_acronyms;

    /** Initialise the ACL and contexts
    */ 
    public function init() {
    $this->_helper->_acl->allow('fa',null);
    $this->_helper->_acl->allow('admin',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_acronyms = new Acronyms();
    }

    const REDIRECT = '/admin/acronyms/';
    /** Display all the acronyms
    */ 
    public function indexAction(){
    $this->view->acronyms = $this->_acronyms->getAllAcronyms($this->_getAllParams());
    }
    /** Add a new acronym
    */ 	
    public function addAction()	{
    $form = new AcronymForm();
    $form->details->setLegend('Add an acronym: ');
    $form->submit->setLabel('Add new acronym');
    $this->view->form = $form;
    if($this->getRequest()->isPost() 
        && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $this->_acronyms->add($form->getValues());
    $this->_flashMessenger->addMessage('A new acronym has been created.');
    $this->_redirect(self::REDIRECT);
    } else 	{
    $form->populate($form->getValues());
    }
    }
    }

    /** Edit an acronym
    */ 	
    public function editAction() {
    if($this->_getParam('id',false)) {
    $form = new AcronymForm();
    $form->details->setLegend('Edit an acronym: ');
    $form->submit->setLabel('Save new acronym details');
    $this->view->form = $form;
    if($this->getRequest()->isPost() 
        && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
    $where = array();
    $where[] =  $this->_acronyms->getAdapter()->quoteInto('id = ?', 
            $this->_getParam('id'));
    $update = $this->_acronyms->update($updateData,$where);
    $this->_flashMessenger->addMessage('Acronym details updated.');
    $this->_redirect(self::REDIRECT);
    } else {
    $form->populate($updateData);
    }
    } else {
    // find id is expected in $params['id']
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
    $acro = $this->_acronyms->fetchRow('id=' . $id)->toArray();
    $this->view->acro = $acro;
    $form->populate($acro);
    }
    }
    } else {
            throw new Exception($this->_missingParameter);
    }
    }
    /** Delete an acronym
    */ 	
    public function deleteAction(){
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = 'id = ' . $id;
    $this->_acronyms->delete($where);
    }
    $this->_redirect(self::REDIRECT);
    $this->_flashMessenger->addMessage('Record deleted!');
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->acro = $this->acronyms->fetchRow('id='.$id);
    }
    }
    }	
	

}