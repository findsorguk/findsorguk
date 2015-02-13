<?php
/** ACL integration building on an example by Rob Allen.
 *
 * An example of code use:
 * 
 * <code>
 * <?php
 * $this->_helper->acl->allow('public', null);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @license    http://framework.zend.com/license/new-bsd  New BSD License
 * @example /app/modules/about/controllers/ContactusController.php 
 * 
 */
class Pas_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /** The action
     * @var \Zend_Controller_Action
     */
    protected $_action;

    /** The auth object
     * @var \Zend_Auth
     */
    protected $_auth;

    /** The Action Control Object
     * @var \Zend_Acl
     */
    protected $_acl;

    /** The controller name
     * @var string
     */
    protected $_controllerName;
    
    /** Get the action
     * @access public
     * @return string
     */
    public function getAction() {
        $this->_action = $this->getActionController();
        return $this->_action;
    }

    /** Get the auth object
     * @access public
     * @return \Zend_Auth
     */
    public function getAuth() {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
    }

        
    /**  Constructor
     * Optionally set view object and options.
     * @access public
     * @param  Zend_View_Interface $view
     * @param  array $options
     * @return void
     */
    public function __construct(Zend_View_Interface $view = null, array $options = array()) {
        $this->_acl = $options['acl'];
    }

    /**
     * Hook into action controller initialization
     *
     * @return void
     */
    public function init() {
        // add resource for this controller
        $controller = $this->getAction()->getRequest()->getControllerName();
        if(!$this->_acl->has($controller)) {
            $this->_acl->add(new Zend_Acl_Resource($controller));
        }

    }

    /**
     * Hook into action controller preDispatch() workflow
     *
     * @return void
     */
    public function preDispatch()  {
        $role = 'public';
        if ($this->getAuth()->hasIdentity()) {
            $user = $this->getAuth()->getIdentity();
            if(is_object($user)) {
                $role = $this->getAuth()->getIdentity()->role;

            }
        }
        $request = $this->getAction()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();
        $this->_controllerName = $controller;

        $resource = $controller;
        $privilege = $action;

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        if (!$this->_acl->isAllowed($role, $resource, $privilege)) {
            $request->setModuleName('default');
            $request->setControllerName('error');
            $request->setActionName('notauthorised');
            $request->setDispatched(false);
        }
    }

    /** Proxy to the underlying Zend_Acl's allow()
     * We use the controller's name as the resource and the
     * action name(s) as the privilege(s)
     * @access public
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  string|array                             $actions
     * @uses   Zend_Acl::setRule()
     * @return Pas_Controller_Action_Helper_Acl Provides a fluent interface
     */
    public function allow($roles = null, $actions = null) {
        $resource = $this->_controllerName;
        $this->_acl->allow($roles, $resource, $actions);
        return $this;
    }

    /** Proxy to the underlying Zend_Acl's deny()
     * We use the controller's name as the resource and the
     * action name(s) as the privilege(s)
     * @access public
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  string|array                             $actions
     * @uses   Zend_Acl::setRule()
     * @return Pas_Controller_Action_Helper_Acl Provides a fluent interface
     */
    public function deny($roles = null, $actions = null) {
        $resource = $this->_controllerName;
        $this->_acl->deny($roles, $resource, $actions);
        return $this;
    }
}