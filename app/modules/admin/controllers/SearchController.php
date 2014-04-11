<?php
/** Controller for manipulating search queries
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_SearchController extends Pas_Controller_Action_Admin {

	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Paginated list of searches made
	*/
	public function indexAction()  {
	$search = new Searches();
	$this->view->searches = $search->getAllSearchesAdmin($this->_getParam('page'));
    }
}
