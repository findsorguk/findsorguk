<?php
class Pas_Controller_Plugin_Errors extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
    	$route = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRoute();
		if($route instanceOf Zend_Rest_Route){
    	$frontController = Zend_Controller_Front::getInstance();

        $error = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler');

        $error->setErrorHandlerModule($request->getModuleName());
		}
    }
}