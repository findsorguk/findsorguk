<?php
/** Controller for scrollintg through users. Minimum access to members only.
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_NamedController extends Pas_Controller_Action_Admin {

        protected $_users;

        /** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('member',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_users = new Users();

        }
	/** Set up the index page
	*/
	public function indexAction(){
	$this->view->users = $this->_users->getUsersAdmin($this->_getAllParams());
	}
	/** View the individual person's account
	*/
	public function personAction() {
	if($this->_getParam('as',0)){
	$id = $this->_getParam('as');
	$this->view->accountdata = $this->_users->getUserAccountData($id);
	$this->view->totals = $this->_users->getCountFinds($this->getIdentityForForms());
	
	} else {
            throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
