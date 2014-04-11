<?php

class Api_IndexController extends Pas_Controller_Action_Admin
{
	protected $higherLevel = array('admin','flos'); 
	protected $researchLevel = array('member','heros','research');
	protected $restricted = array('public');


	public function init() {
	$this->_helper->_acl->allow(null);
    }
	
	public function indexAction(){
 	$content = new Content();
	$this->view->content = $content->getFrontContent('api');
    }
}