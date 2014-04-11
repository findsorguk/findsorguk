<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AllowedByAclLink
 *
 * @author danielpett
 */
class Pas_View_Helper_AllowedByAclLink extends Zend_View_Helper_Abstract {

  public function allowedByAclLink($title, $action, $controller=null, $module=null, $args=array(), $classes="")
  {
      $request = Zend_Controller_Front::getInstance()->getRequest();
      if(!$controller)
        $controller = $request->getControllerName();

      if(!$module)
        $module = $request->getModuleName();

      $acl = Zend_Registry::get('acl');
      if($acl->isUserAllowed(
        $module,
        $controller,
        $action)) {

      	$arg = array_merge($args,
      		array('module'=>$module, 'controller'=>$controller, 'action'=>$action));
      	return "<a href=" . $this->view->url($arg) . " class=" . $classes . ">" . $title . "</a>";
      } else {
      	return null;
      }
  }
}