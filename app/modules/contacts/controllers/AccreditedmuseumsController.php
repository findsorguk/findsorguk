<?php
/** Controller for coroner based data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Contacts_AccreditedMuseumsController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
			 ->addActionContext('index',$contexts)
             ->initContext();
	}

	/** Set up data for coroners index page
	*/
	public function indexAction() {
	$museums = new AccreditedMuseums();
	$this->view->museums =  $museums->listMuseums($this->_getAllParams());
	}
	
	public function museumAction(){
		$museum = new AccreditedMuseums();
		$this->view->museum = $museum->fetchRow('id = ' . $this->_getParam('id'));
	}

	public function mapAction(){
		
	}
}