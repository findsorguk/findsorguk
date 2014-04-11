<?php

class Api_ObjectsController extends REST_Controller
{

	protected $_context = 'xml';
	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	$this->_helper->contextSwitch()->removeContext('html');
	$this->view->baseUrl = Zend_Registry::get('siteurl');
    }
    	
	
	public function indexAction(){
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler('objects');
	$fields = new Pas_Solr_FieldGeneratorFinds($this->_helper->contextSwitch->getCurrentContext());
	$search->setFields($fields->getFields());
	$search->setParams($params);
	$search->execute();
	$this->view->params = $this->_getAllParams();
	$this->view->pagination = $this->createPagination($search->_createPagination());
	$this->view->stats = $search->_processStats();
	$this->view->results = $search->_processResults();
		
	
	$this->_response->ok();
    }
    
    private function createPagination($paginator){
    	$pagination = array(
    	'currentPage' => $paginator->getCurrentPageNumber(),
    	'totalResults' => $paginator->getTotalItemCount(), 
    	'resultsPerPage' => $paginator->getItemCountPerPage()
    	);
    	return $pagination;
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
    	$params = $this->_getAllParams();
		$search = new Pas_Solr_Handler('objects');
		$fields = new Pas_Solr_FieldGeneratorFinds($this->_helper->contextSwitch->getCurrentContext());
		$search->setFields($fields->getFields());
		$search->setParams(array('id' =>  $this->_getParam('id')));
		$search->execute();
		$this->view->params = $this->_getAllParams();
		$this->view->pagination = $this->createPagination($search->_createPagination());
		$this->view->stats = $search->_processStats();
		$this->view->results = $search->_processResults();
    	$this->_response->ok();
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