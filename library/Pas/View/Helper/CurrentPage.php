<?php
/**
 * A view helper for creating active css class for current page
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2014 mchester-kadwell @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @example    echo $this->active('contacts', 'index', 'index');
 */

class Pas_View_Helper_CurrentPage extends Zend_View_Helper_Abstract {
   
    protected $_module;
    protected $_controller;
    protected $_action;
 
    /** Get the current instance of module, controller and action
     *  
     */    
    public function __construct(){    
        $front = Zend_Controller_Front::getInstance()->getRequest();
        $this->_module = $front->getModuleName();
        $this->_controller = $front->getControllerName();
        $this->_action = $front->getActionName();
    }

    public function __toString() {
        return $this->active();
    }    
    
    public function currentPage() {
        return $this;
    }
    
    /** Create active css class for link if current instance matches 
     *  
     */
    public function active($module, $controller, $action){
        if($module == $this->_module && $controller == $this->_controller && $action == $this->_action) {
            return 'class="active"';
        }
    }
    
}