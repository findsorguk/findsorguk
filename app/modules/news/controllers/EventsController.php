<?php

/** Index controller for PAS based events module
*
* @category		Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) 2011 Daniel Pett
* @license		GNU General Public License
* @author		Daniel Pett
* @since		23 Sept. 2011
* @version		1.0
*/
class Events_IndexController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL for access levels
	*/
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	}
		
	 /**
	* Render data for view on index action
	*/
	function indexAction() {
	$this->view->headTitle('Events at the Scheme');
 	$content = new Content();
	$this->view->contents = $content->getFrontContent('events');

	$year = strftime("%Y", strtotime(Zend_Date::now()->toString('yyyy-MM-dd')));
	$this->view->year = $year;
	$adults = new Events();
	$this->view->adults = $adults->getAttendanceAdults($year.'-01-01',$year.'-12-31');
	
	$eventsList = new Events();
	$eventsListed = $eventsList->getUpcomingEvents();
	$calendar = new Calendar;
	$lists = array();
	foreach ($eventsListed as $value)
	{
	$lists[] = $value['eventStartDate'];
	}
	$listedDates = $lists;
	$calendar->highlighted_dates = $listedDates;
	$url = $this->view->url(array('module' => 'events','controller' => 'upcoming','action' => 'index'),'upcoming',true);
	$calendar->formatted_link_to = $url.'/%Y-%m-%d';
	$cal = '<div id="calendars" style="float:right;margin-top:100px;margin-left:10px;">'. ($calendar->output_calendar()). '</div>';
	$this->view->cal =$cal;

	}



}