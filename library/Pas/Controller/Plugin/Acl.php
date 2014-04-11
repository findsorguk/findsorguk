<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Acl */
require_once 'Zend/Acl.php';

/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * Front Controller Plugin
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
class Pas_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Acl
     **/
    protected $_acl;

    /**
     * @var string
     **/
    protected $_roleName;

    /**
     * @var array
     **/
    protected $_deniedAction;

    /**
     * @var boolean
     **/
    protected $_throwExceptions = FALSE;

    /**
     * @var boolean
     **/
    protected $_denyUnknown = FALSE;

    /**
     * Constructor
     *
     * @param Zend_Acl $aclData
     * @param $roleName
     * @return void
     **/
    public function __construct(Zend_Acl $aclData, $roleName = 'public')
    {
        $this->_roleName = $roleName;

        if (NULL !== $aclData) {
            $this->setAcl($aclData);
        }

        $front = Zend_Controller_Front::getInstance();

        /** If an error handler hasn't been setup in the front controller, setup one */
        if (!$front->getParam('noErrorHandler') && !$front->hasPlugin('Zend_Controller_Plugin_ErrorHandler')) {
            // Register with stack index of 100
            require_once 'Zend/Controller/Plugin/ErrorHandler.php';
            $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(), 100);
        }

        /** Allow error handler in the acl */
        $errorHandler = Zend_Controller_Front::getInstance()->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $defaultErrorModule = $errorHandler->getErrorHandlerModule();
        $defaultErrorController = $errorHandler->getErrorHandlerController();
        $defaultErrorAction = $errorHandler->getErrorHandlerAction();

        if (NULL !== $defaultErrorModule && $defaultErrorModule != 'default') {
            if (!$this->getAcl()->has($defaultErrorModule)) {
                require_once 'Zend/Acl/Resource.php';
                $this->_acl->add(new Zend_Acl_Resource($defaultErrorModule));
                $this->_acl->add(new Zend_Acl_Resource($defaultErrorModule . ':' . $defaultErrorController, $defaultErrorModule));
                $this->_acl->allow($this->_roleName, $defaultErrorModule . ':' . $defaultErrorController, $defaultErrorAction);
            }
        } else {
            if (!$this->getAcl()->has($defaultErrorController)) {
                require_once 'Zend/Acl/Resource.php';
                $this->_acl->add(new Zend_Acl_Resource($defaultErrorController));
            }

            $this->_acl->allow($this->_roleName, $defaultErrorController, $defaultErrorAction);
        }

        $this->setDeniedAction('denied', $defaultErrorController, $defaultErrorModule);
    }

    /**
     * Sets the ACL object
     *
     * @param Zend_Acl $aclData
     * @return void
     **/
    public function setAcl(Zend_Acl $aclData)
    {
        $this->_acl = $aclData;
    }

    /**
     * Returns the ACL object
     *
     * @return Zend_Acl
     **/
    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * Sets the ACL role to use
     *
     * @param string $roleName
     * @return void
     **/
    public function setRoleName($roleName)
    {
        $this->_roleName = $roleName;
    }

    /**
     * Returns the ACL role used
     *
     * @return string 
     **/
    public function getRoleName()
    {
        return $this->_roleName;
    }

    /**
     * Sets the denied action
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @return void
     **/
    public function setDeniedAction($action, $controller = NULL, $module = NULL)
    {
    	/** Initialize deniedAction array */
    	if (!is_array($this->_deniedAction)) {
    		$this->_deniedAction = array('module' => NULL, 
    		                             'controller' => NULL, 
    		                             'action' => NULL);
    	}

    	if (NULL !== $module) {
    		$this->_deniedAction['module'] = $module;
    	}

    	if (NULL !== $controller) {
    		$this->_deniedAction['controller'] = $controller;
    	}

        $this->_deniedAction['action'] = $action;
    }

    /**
     * Returns the denied action
     *
     * @return array
     **/
    public function getDeniedAction()
    {
        return $this->_deniedAction;
    }

    /**
     * Set throw exceptions flag
     * 
     * @param boolean $flag
     * @return boolean|ZendL_Controller_Plugin_Acl Used as a setter, returns object; as a getter, returns boolean
     **/
    public function throwExceptions($flag = NULL)
    {
        if ($flag !== NULL) {
            $this->_throwExceptions = (bool) $flag;
            return $this;
        }

        return $this->_throwExceptions;
    }

    /**
     * Set deny unknown flag
     * 
     * @param boolean $flag
     * @return boolean|ZendL_Controller_Plugin_Acl Used as a setter, returns object; as a getter, returns boolean
     **/
    public function denyUnknown($flag = NULL)
    {
        if ($flag !== NULL) {
            $this->_denyUnknown = (bool) $flag;
            return $this;
        }

        return $this->_denyUnknown;
    }

    /**
     * Predispatch
     * Checks if the current user identified by roleName has rights to the requested url (module/controller/action)
     * If not, it will call denyAccess to be redirected to errorPage
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     **/
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        /** Check if the given model/controller/action can be dispatched */
        if (!Zend_Controller_Front::getInstance()->getDispatcher()->isDispatchable($request)) {
            if (TRUE === $this->denyUnknown()) {
                $this->denyAccess();
            }
        } else {
            $resourceName = '';

            if ($request->getModuleName() != 'default' && $request->getModuleName() != '') { 
                $resourceName .= $request->getModuleName() . ':';
            }

            $resourceName .= $request->getControllerName();

            /** Check if the module/controller/action exists in the acl */
            if (!$this->getAcl()->has($resourceName)) {
                $this->denyAccess('Resource (' . $resourceName . ') was not found in the Acl');
            } else {
                /** Check if the module/controller/action can be accessed by the current user */
                if (!$this->getAcl()->isAllowed($this->_roleName, $resourceName, $request->getActionName())) {
                    /** Redirect to access denied page */
                    $this->denyAccess();
                }
            }
        }
    }

    /**
     * Deny Access Function
     * Redirects to deniedAction, this can be called from an action using the action helper
     *
     * @param string|NULL $message
     * @return void
     **/
    public function denyAccess($message = 'You are not authorized for access this page.')
    {
        if ($this->_throwExceptions == FALSE) {
        	$deniedAction = $this->getDeniedAction();

            $this->getRequest()->setModuleName($deniedAction['module']);
            $this->getRequest()->setControllerName($deniedAction['controller']);
            $this->getRequest()->setActionName($deniedAction['action']);
            $this->getRequest()->setParam('message', $message);
        } else {
            $errorHandler = Zend_Controller_Front::getInstance()->getPlugin('Zend_Controller_Plugin_ErrorHandler');

            $this->getRequest()->setModuleName($errorHandler->getErrorHandlerModule());
            $this->getRequest()->setControllerName($errorHandler->getErrorHandlerController());
            $this->getRequest()->setActionName($errorHandler->getErrorHandlerAction());

            throw new Exception($message);
        }
    }
}