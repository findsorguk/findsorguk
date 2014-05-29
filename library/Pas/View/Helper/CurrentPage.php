<?php
/**
 * A view helper for creating active css class for current page
 * 
 * An example usage of this helper:
 * <code>
 * <?php 
 * echo $this->currentPage()->active('users','configuration','index');
 * ?>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2014 mchester-kadwell @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author     Mary Chester-Kadwell
 * @example    echo $this->currentPage()->active('contacts', 'index', 'index', 'slug');
 */

class Pas_View_Helper_CurrentPage extends Zend_View_Helper_Abstract
{
    /** The module of the request
     * @access protected
     * @var string
     */
    protected $_module;
    
    /** The controller of the request
     * @access protected
     * @var string
     */
    protected $_controller;
    
    /** The action of the request
     * @access protected
     * @var string
     */
    protected $_action;
    
    /** Get a param from the request
     * @access protected
     * @var type 
     */
    protected $_param;

    /** The front controller
     * @access protected
     * @var \Zend_Controller_Front
     */
    protected $_front;
    
    /** Get the module of the request
     * @access public
     * @return string The module
     */
    public function getModule() {
        $this->_module = $this->getFront()->getModuleName();
        return $this->_module;
    }

    /** Get the controller of the request
     * @access public
     * @return string
     */
    public function getController() {
        $this->_controller = $this->getFront()->getControllerName();
        return $this->_controller;
    }

    /** Get the action of the request
     * @access public
     * @return string
     */
    public function getAction() {
        $this->_action = $this->getFront()->getActionName();
        return $this->_action;
    }

    public function getParam() {
        $this->_param = $this->getFront()->getParam('slug');
        return $this->_param;
    }

    /** Get the parameter of the request
     * @access public
     * @return string
     */
    public function getFront() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_front;
    }

    /** The to string function rendering active
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->active();
    }

    /** The current page function
     * @access public
     * @return \Pas_View_Helper_CurrentPage
     */
    public function currentPage() {
        return $this;
    }

    /** Create active css class for link if current instance matches
     *  @param string $module
     *  @param string $controller
     *  @param string $action
     *  $param string $param
     */
    public function active($module = null, $controller = null, $action = null, $param = null)
    {
        if ($module == $this->getModule() && $controller == $this->getController() 
                && $action == $this->getAction()) {
            return 'class="active"';
        } elseif ($module == $this->getModule() && $controller == $this->getController() 
                && $action == $this->getAction() && $this->getParam() == $param) {
           return 'class="active"';
        }
    }

}
