<?php
/** Controller for accessing specific user details for IP logins etc
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_BackupsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init()  {
	$this->_helper->_acl->allow('flos',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	
	public function indexAction() {

	}
	
}
