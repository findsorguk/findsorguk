<?php

class Secrettreasures_IndexController extends Pas_Controller_Action_Admin
{
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow(null);
	}



	public function indexAction() {
            $content = new Content();
            $this->view->contents = $content->getFrontContent('secret');
	}

}