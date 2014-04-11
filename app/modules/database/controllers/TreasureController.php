<?php
/** Controller for treasure module
 * @todo finish module's functions
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_TreasureController extends Pas_Controller_Action_Admin {
	
	protected $_treasureID, $_redirect;
	
	public function init(){
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');	
	$this->_helper->_acl->allow('flos',null);
	$this->_treasureID = $this->_getParam('treasureID');
	$this->view->id = $this->_treasureID;
	$this->_redirect = $this->view->url(array(
	'module' => 'database',
	'controller' => 'treasure',
	'action' => 'casehistory',
	'treasureID' => $this->_treasureID)
	,null,true);
	}
	
	public function indexAction() {
	$this->_flashMessenger->addMessage('There is no direct access to the root action for treasure');
	$this->_redirect('/treasure/cases/');
	}
	
	public function casehistoryAction(){
	if($this->_getParam('treasureID',false)){	
	$treasure = new TreasureCases();
	$this->view->cases = $treasure->getCaseHistory($this->_treasureID);
	$valuations = new TreasureValuations();
	$this->view->values = $valuations->listvaluations($this->_treasureID);
	$curators = new TreasureAssignations();
	$this->view->curators = $curators->listCurators($this->_treasureID);
	$committees = new TvcDatesToCases();
	$this->view->tvcs = $committees->listDates($this->_treasureID);
	$actions = new TreasureActions();
	$this->view->actions = $actions->getActionsListed($this->_treasureID);
	$finals = new AgreedTreasureValuations();
	$this->view->finalvalues = $finals->listvaluations($this->_treasureID);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	public function eventAction(){
	if($this->_getParam('treasureID',false)){	
		
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	public function editeventAction(){
	if($this->_getParam('treasureID',false)){	
		
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	
	public function provisionalvalueAction(){
	if($this->_getParam('treasureID',false)){	
	$form = new ProvisionalValuationForm();
	$form->submit->setLabel('Add valuation');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$provisionals = new TreasureValuations();
	$insert = $provisionals->add($data);
	$this->_redirect($this->_redirect);
	$this->_flashMessenger->addMessage('A new provisional value has been added.');
	} else {
	$form->populate($formData);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	
	public function editprovisionalvalueAction(){
		
	$form = new ProvisionalValuationForm();
	$form->submit->setLabel('Change valuation');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$provisionals = new TreasureValuations();
	$insert = $provisionals->updateTreasure($data);
	$this->_redirect($this->_redirect);
	$this->_flashMessenger->addMessage('A provisional value has been updated.');
	} else {
	$form->populate($formData);
	}
	} else {
	$provisionals = new TreasureValuations();
	$edit = $provisionals->fetchRow($provisionals->select()->where('treasureID = ?', $this->_treasureID));
	$form->populate($edit->toArray());
	}
	}
	
	public function deleteprovisionalvalueAction(){
		
	}
	
	public function assigncuratorAction(){
	if($this->_getParam('treasureID',false)){	
	$form = new TreasureAssignForm();
	$form->submit->setLabel('Assign to curator');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$curators = new TreasureAssignations();
	$insert = $curators->add($data);
	$this->_redirect($this->_redirect);
	$this->_flashMessenger->addMessage('Curator has been assigned.');
	} else {
	$form->populate($formData);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	
	public function editcuratorAction(){
		
	}
	
	public function deletecuratorAction(){
		
	}
	
	public function chasecuratorAction(){
		
	}
	
	public function tvcAction(){
	if($this->_getParam('treasureID',false)){	
	$form = new TVCDateForm();
	$form->submit->setLabel('Assign to meeting date');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$dates = new TvcDatesToCases();
	$insert = $dates->add($data);
	$this->_redirect($this->_redirect);
	$this->_flashMessenger->addMessage('Curator has been assigned.');
	} else {
	$form->populate($formData);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}		
	}
	
	public function actionAction(){
	if($this->_getParam('treasureID',false)){	
	$form = new TreasureActionForm();
	$form->submit->setLabel('Add an action taken');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$actions = new TreasureActions();
	$insert = $actions->add($data);
	$this->_redirect($this->_redirect);
	$this->_flashMessenger->addMessage('New course of action added.');
	} else {
	$form->populate($formData);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}			
	}

	public function finalAction(){
	if($this->_getParam('treasureID',false)){	
	$form = new FinalValuationForm();
	$form->submit->setLabel('Add final valuation');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$provisionals = new AgreedTreasureValuations();
	$insert = $provisionals->add($data);
	$this->_redirect($this->_redirect);
	$this->_flashMessenger->addMessage('A new final valuation has been added.');
	} else {
	$form->populate($formData);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
}


