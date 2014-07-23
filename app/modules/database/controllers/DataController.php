<?php
/** Controller for accessing specific user details for IP logins etc
*
* @category   Pas
* @package Pas_Controller_Action
* @subpackage Admin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Database_DataController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init()  {
	$this->_helper->_acl->allow('member','index');
	$this->_helper->_acl->allow('flos','backups');
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	
	public function indexAction() {

	}
	
	public function backupsAction(){
	}
	
}
