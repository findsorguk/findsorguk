<?php 
/** Controller for getting the guides that the Scheme produces
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Getinvolved_GuidesController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL
	*/ 
    public function init() {
		$this->_helper->acl->allow(null);
    }
	/** Get data for the Index action
	*/ 
	function indexAction() {
		$content = new Content();
		$this->view->contents = $content->getContent('getinvolved',$this->getRequest()->getParam('slug'));	
	}
}
