<?php 
/** Controller for getting links via delicious
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Getinvolved_LinksController extends Pas_Controller_Action_Admin {
    
	/** Initialise the ACL
	*/ 
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow(null);
    }
	/** Render data for the index page
	*/ 
	function indexAction() {
		$this->view->page = $this->_getParam('page');
	}
	/** Render data by tag for link page
	*/ 
	function linkAction() {
		$this->view->tag = $this->_getParam('bytag');
	}

}