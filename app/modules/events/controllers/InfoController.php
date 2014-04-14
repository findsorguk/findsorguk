<?php
/** The information controller for the events package
*
* @category		Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) 2011 Daniel Pett
* @license		GNU General Public License
* @author		Daniel Pett
* @since		23 Sept 2011
* @version		1	
*/
class Events_InfoController extends Pas_Controller_Action_Admin {

	/**
	* Initialise the ACL for access levels and the context switches
	*/
    public function init() {
       	$contexts = array('xml','json','ics');
	  	$contextSwitch = $this->_helper->contextSwitch();
		$contextSwitch->addContext('ics',array('suffix' => 'ics'))
	  	     ->addActionContext('index', $contexts)
             ->initContext();
		$this->_helper->acl->allow('public',null);
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/**
	* Render data for view on index action
	*/	
	function indexAction() {
	$events = new Events();
	$this->view->events = $events->getEventData($this->_getParam('id'));
	}


}