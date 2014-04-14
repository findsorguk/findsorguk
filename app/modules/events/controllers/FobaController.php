<?php

/** Festival of British Archaeology Controller
* 
* This can probably be rewritten, with years and archives added
*
* @category		Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) 2011 Daniel Pett
* @license		GNU General Public License
* @todo			Rewrite the controller
* @author		Daniel Pett
* @since		23 Sept. 2011
* @version		1.0
*/
class Events_FobaController extends Pas_Controller_Action_Admin {

	/**
	* Initialise the ACL for access levels
	*/
	public function init() {
	$this->_helper->_acl->allow(NULL);
    }

    /**
	* Render data for view on index action
	*/
	public function indexAction()	{
	$events = new Events();
	$this->view->events = $events->getEventByType(12,2011);
	}
}
