<?php
class Api_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initREST()
    {
        $frontController = Zend_Controller_Front::getInstance();
		
	    
//         register the RestHandler plugin
        $frontController->registerPlugin(new REST_Controller_Plugin_RestHandler($frontController));
		
        $restSwitch = new Pas_Controller_Action_Helper_RestContextSwitch();
        Zend_Controller_Action_HelperBroker::addHelper($restSwitch);
        
        $restContexts = new REST_Controller_Action_Helper_RestContexts();
        Zend_Controller_Action_HelperBroker::addHelper($restContexts);
    }
}