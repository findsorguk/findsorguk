<?php
/** Controller for getting lists of error reports submitted by public
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_ErrorsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Display the index page
	*/	
	public function indexAction() {
	$this->view->params = $this->_getAllParams();
	$errors = new ErrorReports();
	$this->view->errors = $errors->getMessages($this->_getAllParams());
	}
	
}
