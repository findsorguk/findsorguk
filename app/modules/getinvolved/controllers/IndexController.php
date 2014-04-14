<?php
/** Controller for index of the get involved module
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_IndexController extends Pas_Controller_Action_Admin {
	
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->initView();
		$this->view->messages = $this->_flashMessenger->getMessages();
		$this->_helper->acl->allow('public',null);
	}
		
	function indexAction() {
 		$content = new Content();
		$this->view->contents = $content->getFrontContent('getinvolved');
	}

}
