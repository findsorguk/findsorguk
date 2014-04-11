<?php

class Pas_Controller_Plugin_SearchForm
	extends Zend_Controller_Plugin_Abstract {
		
		
	public function postDispatch(Zend_Controller_Request_Abstract $request) {
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$this->view->form = new SiteWideForm();
	
	}
		
		
	}