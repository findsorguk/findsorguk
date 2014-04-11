<?php
/** Controller for the Staffordshire symposium
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class Staffshoardsymposium_IndexController extends Pas_Controller_Action_Admin {
	
	/**
	 * Set up the ACL
	 */
	public function init() {
		$this->_helper->_acl->allow('public',null);	
	}
	
	/** List of the papers available
	 */
	public function indexAction() {
		$content = new Content();
		$this->view->front = $content->getFrontContent('staffs', 1, 3);
		$this->view->contents = $content->getSectionContents('staffs');
	}

}

