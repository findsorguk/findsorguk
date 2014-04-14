<?php
/** Controller for getting information on volunteer roles
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_VolunteeringController extends Pas_Controller_Action_Admin {
	
	protected $_volunteers;
	
	/** Initialise the ACL and set up contexts
	*/ 
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
  		 ->addContext('rss',array('suffix' => 'rss'))
		 ->addContext('atom',array('suffix' => 'atom'))
		 ->addActionContext('index', array('xml','json','rss','atom'))
  		 ->addActionContext('role', array('xml','json'))
  		 ->initContext();
    $this->_volunteers = new Volunteers();         
	}
		
	/** Render the index page
	*/ 
	public function indexAction() {
	$this->view->vols = $this->_volunteers->getCurrentOpps($this->_getAllParams());
	}
	
	/** Render individual role
	*/ 
	public function roleAction(){
	if($this->_getParam('id',false)){
		$this->view->vols = $this->_volunteers->getOppDetails($this->_getParam('id'));
	} else {
			throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}