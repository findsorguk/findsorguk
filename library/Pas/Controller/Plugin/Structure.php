<?php
/**
 * A front controller plugin for rendering the correct layout and structure based on module.
 * @category   Pas
 * @package    Pas_Controller
 * @subpackage Pas_Controller_Plugin
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Pas_Controller_Plugin_Structure
	extends Zend_Controller_Plugin_Abstract {
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
	$module = strtolower($request->getModuleName());
	$contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
	$contexts = array('xml','rss','json','atom','kml','georss','ics','rdf','xcs');
	if(!in_array($contextSwitch->getCurrentContext(),$contexts)){
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$response = $this->getResponse();
		switch($module) {
			case $module == 'default':
				$layouttype = 'home';
			break;
			case $module == 'admin':
				$layouttype = 'database';
			break;
			case $module == 'database':
				$layouttype = 'database';
			break;
			case $module == 'events':
		        $response->insert('userdata', $view->render('structure/userdata.phtml')); 
				$response->insert('header', $view->render('structure/header.phtml'));
				$response->insert('breadcrumb', $view->render('structure/breadcrumb.phtml'));
				$response->insert('sidebar', $view->render('structure/eventsSidebar.phtml'));
				$response->insert('navigation', $view->render('structure/navigation.phtml'));
				$response->insert('footer', $view->render('structure/footer.phtml'));
				$response->insert('messages', $view->render('structure/messages.phtml'));
			default:
				$layouttype = 'database';
			break;
		}
		}  else {
		Zend_Controller_Action_HelperBroker::getExistingHelper('Layout')->disableLayout(); 
		}	
	}
	
	public function postDispatch()  {
	$this->view->messages = $this->messenger->getCurrentMessages();
    }
}

