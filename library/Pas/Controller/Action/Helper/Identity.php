<?php
 /**
 * ACL integration
 *
 * Places_Controller_Action_Helper_Acl provides ACL support to a 
 * controller.
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @package    Controller
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2007,2008 Rob Allen
 * @license    http://framework.zend.com/license/new-bsd  New BSD License
 */
class Pas_Controller_Action_Helper_Identity extends Zend_Controller_Action_Helper_Abstract {


	protected $_auth;
	
	public function init(){
	$this->_auth = Zend_Auth::getInstance();	
	}
	
	public function getIdentityForForms() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	public function getPerson(){
	if($this->_auth->hasIdentity()) {
	return $this->_auth->getIdentity();
	} else {
	return false;
	}	
	}
	
	public function direct()
	{
		return $this->getPerson();
	}
}