<?php
/** Controller for manipulating the FAQ data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_FaqController extends Pas_Controller_Action_Admin {
	
	/** Initialise the ACL and setup contexts
	*/ 
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow('public',null);
		$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
		$this->_helper->contextSwitch()->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
             ->initContext();
	}
		
	public function indexAction() {
		$faqs = new Faqs();
		$this->view->faqs = $faqs->getAll();
	}

}
