<?php
/** Controller for displaying search history for a user's account
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_SearchController extends Pas_Controller_Action_Admin {
	/** Setup the ACL
	*/	
	public function init() {	
	$this->_helper->_acl->deny('public');
	$this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Setup the index display pages
	*/	
	public function indexAction() {
	$search = new Searches();
	$this->view->tops = $search->getTopSearch((int)$this->getIdentityForForms());
	$this->view->quantity = $search->getTopSearchQuantity((int)$this->getIdentityForForms());
	}
	/** Display the search history for a user
	*/	
	public function historyAction() {
	$search = new Searches();
	$this->view->searches = $search->getAllSearches((int)$this->getIdentityForForms(),
	(int)$this->_getParam('page'));
	}
	/** Display saved searches by logged in account
	*/		
	public function savedAction() {
	$search = new Searches();
	$this->view->searches = $search->getAllSavedSearches( 
	$this->getIdentityForForms(),
	$this->_getParam('page'), 
	null
	);
	Zend_Debug::dump($this->view->searches);
	}
}
