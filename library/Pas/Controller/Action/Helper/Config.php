<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Katiebear
 */
class Pas_Controller_Action_Helper_Config 
    extends Zend_Controller_Action_Helper_Abstract {
    
    public function __construct(){
        
    }
    
    public function _getConfig(){
        return Zend_Registry::get('config');
    }
    
    public function direct(){
        return $this->_getConfig(); 
    }
}
