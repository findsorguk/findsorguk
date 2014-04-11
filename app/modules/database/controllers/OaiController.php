<?php

/**
 * Request page controller
 *
 *
 * @uses Pas_OaiPmhRepository_ResponseGenerator
 */
class Database_OaiController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction() {
	$this->_helper->layout->setLayout('database');
	$response = $this->getResponse();
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$response->insert('sidebar', $view->render('structure/infoSidebar.phtml'));
	$response->insert('userdata', $view->render('structure/userdata.phtml'));
	$response->insert('header', $view->render('structure/header.phtml'));
	$response->insert('breadcrumb', $view->render('structure/breadcrumb.phtml'));
	$response->insert('navigation', $view->render('structure/navigation.phtml'));
	$response->insert('footer', $view->render('structure/footer.phtml'));
	$response->insert('messages', $view->render('structure/messages.phtml'));
	$response->insert('contexts',$view->render('structure/contexts.phtml'));
	$response->insert('analytics',$view->render('structure/analytics.phtml'));
	$content = new Content();
	$this->view->content =  $content->getFrontContent('oai');

	}
	/** Setup the requesy via get request
	*/
    public function requestAction(){
    	$this->_helper->layout->disableLayout();
    	$request = $this->_request;
        switch($request)  {
            case $request->isGet():
                // $query = &$_GET;
                $query = $this->_getAllParams();
            	break;
            case $request->isPost():
//            	$query = &$_POST;
            	throw new Zend_Exception('Post requests are not valid');
            	break;
            default: die('Error determining request type.');
        }
        $this->getResponse()->setHeader('Content-type', 'text/xml');
        $clean = $this->_remove($this->_getAllParams());
        unset($query['module']);
        unset($query['action']);
        unset($query['controller']);
        $this->view->response = new Pas_OaiPmhRepository_ResponseGenerator($query);
    }

    protected function _remove($array){
       $removeArray = array('module','csrf','controller','action');
       foreach($array as $k => $v){
           if(in_array($k, $removeArray)){
               unset($array['k']);
           }
       }
    return $array;
    }
}
