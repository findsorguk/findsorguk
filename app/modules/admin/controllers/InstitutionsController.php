<?php
/** Controller for adding and manipulating institutional data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_InstitutionsController extends Pas_Controller_Action_Admin {

	protected $_institutions;
	
	protected $_redirectUrl = 'admin/';
	/** Set up the ACL and contexts
	*/		
	public function init() {
		$flosActions = array('index');
		$this->_helper->_acl->allow('admin',null);
 		$this->_helper->_acl->allow('fa',$flosActions);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_institutions = new Institutions();
    }
    
  	/** Display the index page
	*/	  
	public function indexAction() {
	$this->view->insts = $this->_institutions->getValidInsts($this->_getAllParams());
	}
	/** Add an institution
	*/	
	public function addAction() {
	$form = new InstitutionForm();
	$form->details->setLegend('Add institution details: ');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$this->_institutions->add($form->getValues());
	$this->_flashMessenger->addMessage('A new recording institution has been created.');
	$this->_redirect($this->_redirectUrl . 'institutions/');
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit an institution
	*/	
	public function editAction() {
	$form = new InstitutionForm();
	$form->details->setLegend('Edit institution details: ');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] =  $this->_institutions->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_institutions->update($form->getValues(), $where);
	$this->_flashMessenger->addMessage($form->getValue('institution') . '\'s details updated.');
	$this->_redirect($this->_redirectUrl . 'institutions/');
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$insts = $this->_institutions->fetchRow('id='.$id);
	$this->view->inst = $insts->toArray();
	$form->populate($insts->toArray());
	}
	}
	}
	/** View institutional details
	*/	
	public function institutionAction() {
	$this->view->inst = $this->_institutions->getInst($this->_getParam('id'));
	$users = new Users();
	$this->view->members = $users->getMembersInstitution($this->_getParam('id'));
	}
}