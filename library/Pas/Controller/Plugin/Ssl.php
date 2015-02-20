<?php

class Pas_Controller_Plugin_Ssl extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

        //get the config settings for SSL
        $options = new Zend_Config_Ini(APPLICATION_PATH . '/config/application.ini');

        $secure_modules = explode(',', $options->production->ssl->modules->require_ssl);
        $secure_controllers = explode(',', $options->production->ssl->controllers->require_ssl);

        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();

        // Force SSL Only use it production environment
        if (APPLICATION_ENV == 'production') {
            if ($secure_modules[0] == 'all' || in_array(strtolower($module), $secure_modules) || in_array(strtolower($controller), $secure_controllers)) {
                if (!isset($_SERVER['HTTPS']) && !$_SERVER['HTTPS']) {
                    $url = 'https://' . $_SERVER['HTTP_HOST'] . $request->getRequestUri();
                    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $redirector->gotoUrl($url);
                }
            }
        }
    }
}