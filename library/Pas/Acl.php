<?php
/** Set up the ACL list for theproject
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Acl
 * @license http://URL name
 * @see Zend_Acl
 *
 */
class Pas_Acl extends Zend_Acl {

    /** Construct the roles list
     * @access public
     */
    public function __construct() {
        $config = Zend_Registry::get('config');
	$roles = $config->acl->roles;
	$this->_addRoles($roles);
    }

    /** Add the roles to the ACL
     * @access protected
     * @param type $roles
     */
    protected function _addRoles($roles) {
        foreach($roles as $name => $parents) {
            if(!$this->hasRole($name)) {
                if(empty($parents)){
                    $parents = null;
                } else {
                    $parents = explode(',', $parents);
                }
            $this->addRole(new Zend_Acl_Role($name),$parents);
            }
        }
    }
}