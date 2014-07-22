<?php
/** The OAI controller for dealing with requests for data using the OAI-PMH 
 * protocol. 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @uses Pas_OaiPmhRepository_ResponseGenerator
 * @uses Content
 */
class Datalabs_OaiController extends Pas_Controller_Action_Admin  {
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    /** The index controller
     * @access public
     * @return void
     */
    public function indexAction() {
	$this->_helper->layout->setLayout('database');
	$response = $this->getResponse();
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$response->insert('sidebar', $view->render('structure/datalabsSidebar.phtml'));
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

    /** Setup the request via GET (only request type supported).
     * @access public
     * @return void
     * @throws Pas_Exception
     */
    public function requestAction(){
    	$this->_helper->layout->disableLayout();
    	$request = $this->_request;
        switch($request)  {
            case $request->isGet():
                // GET Query only
                $query = $this->_getAllParams();
            	break;
            case $request->isPost():
                //Forbidden
            	throw new Pas_Exception('Post requests are not valid', 401);
            default: 
                throw new Pas_Exception('Error determining request type', 500);
        }
        $this->getResponse()->setHeader('Content-type', 'text/xml');
        $cleaner = new Pas_ArrayFunctions();
        $clean = $cleaner->array_cleanup($this->_getAllParams());
        $this->view->response = new Pas_OaiPmhRepository_ResponseGenerator($clean);
    }
}