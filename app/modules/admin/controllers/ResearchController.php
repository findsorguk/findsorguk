<?php
/** Controller for adding and manipulating research and topics
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_ResearchController extends Pas_Controller_Action_Admin {

	protected $_research;

	protected $_suggested;
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_research = new ResearchProjects();
	$this->_suggested = new Suggested();
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Set up the redirect baseurl
	 *
	 * @var string REDIRECT
	*/
	const REDIRECT = '/admin/research/';

	public function indexAction(){
	$this->view->research = $this->_research->getAllProjects($this->_getAllParams());
	}
	/** Add a new research topic
	*/
	public function addAction(){
	$form = new ResearchForm();
	$form->submit->setLabel('Add a project');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
        if ($form->isValid($form->getValues())) {
	$this->_research->add($form->getValues());
	$this->_flashMessenger->addMessage('A new research project has been entered.');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a research project
	*/
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new ResearchForm();
	$form->submit->setLabel('Submit changes to project');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$where =  $this->_research->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_research->update($form->getValues(),$where);
	$this->_flashMessenger->addMessage('Research project details updated.');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	} else {

	$form->populate($this->_research->fetchRow('id='.$this->_request->getParam('id'))->toArray());
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Add a suggested research topic
	*/
	public function addsuggestedAction() {
	$form = new SuggestedForm();
	$form->submit->setLabel('Add a project');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
        if ($form->isValid($form->getValues())) {
	$this->_suggested->add($form->getValues());
	$this->_flashMessenger->addMessage('A new suggested research project has been entered.');
	$this->_redirect(self::REDIRECT . 'suggested/');
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** List all suggested topics
	*/
	public function suggestedAction(){
	$this->view->suggested = $this->_suggested->getAll($this->_getAllParams());
	}

	/** Edit a suggested topic
	*/
	public function editsuggestedAction() {
	if($this->_getParam('id',false)) {
	$form = new SuggestedForm();
	$form->submit->setLabel('Submit changes to project');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
    if ($form->isValid($form->getValues())) {
	$where =  $this->_suggested->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $this->_suggested->update($form->getValues(), $where);
	$this->_flashMessenger->addMessage('Suggested research project details updated.');
	$this->_redirect(self::REDIRECT . 'suggested/');
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$form->populate($this->_suggested->fetchRow('id=' . $id)->toArray());
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete a suggested topic
	*/
	public function deletesuggestedAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$suggested = new Suggested();
	$where = $suggested->getAdapter()->quoteInto('id = ?', $id);
	$suggested->delete($where);
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect(self::REDIRECT . 'suggested/');
	}
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$suggested = new Suggested();
	$this->view->suggest = $suggested->fetchRow('id=' . $id);
	}
	}
	}

        public function topicAction()
        {

        }

        public function projectAction(){

            
        }
}
