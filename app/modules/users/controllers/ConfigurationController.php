<?php
/** Controller for configuring which fields to copy.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_ConfigurationController extends Pas_Controller_Action_Admin {
	/** Setup the ACL
	*/	
	public function init() {	
	$this->_helper->_acl->deny('public');
	$this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Setup the index display pages
	*/	
	public function indexAction() {
	//Get the fields from the finds form	
	$finds = new CopyFind();
	$this->view->findFields = $finds->getConfig();
	//Get the fields from the findspot form
	$findspots = new CopyFindSpot();
	$this->view->findSpotFields = $findspots->getConfig();
	//Get the fields for the coin form
	$coins = new CopyCoin();
	$this->view->coinFields = $coins->getConfig();
	}
	
	public function findAction()
	{
	$form = new ConfigureFindCopyForm();	
	$this->view->form = $form;
	$copyFind = new CopyFind();
	$current = $copyFind->getConfig();
	$values = array();
	foreach($current as $cur){
		$values[$cur] = 1;
	}
	$form->populate($values);
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insert = $copyFind->updateConfig($form->getValues()); 
	$this->_flashMessenger->addMessage('Copy last record fields for find table updated');
	$this->_redirect('/users/configuration/');
	} else {
	$form->populate($values);
	}
	}
	}
	
	public function findspotAction()
	{
	$form = new ConfigureFindSpotCopyForm();	
	$this->view->form = $form;
	$copyFindSpot = new CopyFindSpot();
	$current = $copyFindSpot->getConfig();
	$values = array();
	foreach($current as $cur){
		$values[$cur] = 1;
	}
	$form->populate($values);
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insert = $copyFindSpot->updateConfig($form->getValues()); 
	$this->_flashMessenger->addMessage('Copy last record fields for findspot table updated');
	$this->_redirect('/users/configuration/');
	} else {
	$form->populate($values);
	}
	}
	}
	
	public function coinAction()
	{
	$form = new ConfigureCoinCopyForm();
	$this->view->form = $form;
	$copyCoin = new CopyCoin();
	$current = $copyCoin->getConfig();
	$values = array();
	foreach($current as $cur){
		$values[$cur] = 1;
	}
	$form->populate($values);
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insert = $copyCoin->updateConfig($form->getValues()); 
	$this->_flashMessenger->addMessage('Copy last record fields for coin table updated');
	$this->_redirect('/users/configuration/');
	} else {
	$form->populate($values);
	}
	}
	}
}
