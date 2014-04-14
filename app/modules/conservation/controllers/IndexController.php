<?php
/** Controller for displaying index page for the conservation notes module.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class Conservation_IndexController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
		$this->_helper->acl->allow('public',null);
	}
	/** Set up view for index page
	*/ 
	public function indexAction() {
 		$content = new Content();
		$this->view->contents = $content->getFrontContent('conservation');
    }

}
