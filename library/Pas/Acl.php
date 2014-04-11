<?php
/**
 * Set up the ACL list for theproject
 * @category   Pas
 * @package    Acl
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Acl
 */
class Pas_Acl extends Zend_Acl {
	/** Construct the roles list
	*/
	public function __construct() {
	$config = Zend_Registry::get('config');
	$roles = $config->acl->roles;
	$this->_addRoles($roles);
	}
	
	/** Add the roles to the ACL
	* 
	* @param array $roles
	*/
	protected function _addRoles($roles) {
	foreach($roles as $name => $parents) {
	if(!$this->hasRole($name)) {
	if(empty($parents)){
	$parents = null;
	} else {
	$parents = explode(',',$parents);
	}
	$this->addRole(new Zend_Acl_Role($name),$parents);
	}
	}
	}
}