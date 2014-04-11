<?php

class Api_StatusController extends REST_Controller
{

	protected $_context = 'xml';
	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	$this->_helper->contextSwitch()->removeContext('html');
    }
    	
	
	public function indexAction(){
		$this->_response->ok();
    }
    
   	public function headAction()
    {
    	$this->_response->ok();
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction()
    {
    	$this->_response->notImplemented();
    }

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction()
    {
        $this->_response->notImplemented();
    }

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction()
    {
        $this->_response->notImplemented();
    }

    /**
     * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */
    public function deleteAction()
    {
        $this->_response->notImplemented();
    }
}