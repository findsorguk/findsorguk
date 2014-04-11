<?php 

/** Controller for TVC dates and display of data 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Treasure_TvcController extends Pas_Controller_Action_Admin {
	
    protected $_redirect;
    
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow('public',array('index','details'));
		$this->_helper->acl->allow(array('treasure','admin'),NULL);
		$this->_redirect = $this->view->url(array('module' => 'treasure',
		'controller' => 'tvc'),null,true);
    }

	public function indexAction(){
		$dates = new TvcDates();
		$this->view->tvcdates = $dates->listDates($this->_getParam('page'));	
	}
	public function detailsAction() {
	if($this->_getParam('id',false)){
		$id = $this->_getParam('id');
		$tvcdates = new TvcDates();
		$this->view->details = $tvcdates->getDetails($id);
		$this->view->images = $tvcdates->getImages($id);
		$tvccases = new TvcDatesToCases();
		$this->view->cases = $tvccases->listCases($id);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	
	public function addAction(){
		$form = new TVCForm();
		$form->submit->setLabel('Add TVC date');
		$this->view->form = $form;
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {
			$data = $form->getValues();
			$provisionals = new TvcDates();
			$insert = $provisionals->add($data);
			$this->_redirect($this->_redirect);
			$this->_flashMessenger->addMessage('A new provisional value has been added.');
		} else {
			$form->populate($formData);
		}
		}
	}
	
}