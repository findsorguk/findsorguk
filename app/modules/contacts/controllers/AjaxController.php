<?php

/**
 * AjaxController
 * 
 * @author
 * @version 
 */


class Contacts_AjaxController extends Pas_Controller_Action_Ajax {
	
	public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_helper->layout->disableLayout();
	}
	
	public function indexAction() {
		// TODO Auto-generated AjaxController::indexAction() default action
	}

	public function coronersAction(){
		$coroners = new Coroners();
		$data = $coroners->getAll($this->_getAllParams());
		$details = $data->setItemCountPerPage(150);
		$this->view->coroners = $details;
	}
	
	public function museumsAction(){
		$museums = new AccreditedMuseums();
		$this->view->museums  = $museums->mapMuseums();
	}
}

