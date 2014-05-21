<?php
/**
 * A view helper for creating active css class for current page
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
    protected $_module;
    protected $_controller;
    protected $_action;
    protected $_param;

    /** Get the current instance of module, controller and action
     *
     */
    public function __construct()
    {
        $front = Zend_Controller_Front::getInstance()->getRequest();
        $this->_module = $front->getModuleName();
        $this->_controller = $front->getControllerName();
        $this->_action = $front->getActionName();
        $this->_param = $front->getParam('slug');

    }

    public function __toString()
    {
        return $this->active();
    }

    public function currentPage()
    {
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
        if ($module == $this->_module && $controller == $this->_controller && $action == $this->_action) {
            return 'class="active"';
        } elseif ($module == $this->_module && $controller == $this->_controller && $action == $this->_action && $this->_param == $param) {
           return 'class="active"';
        }
    }

}
