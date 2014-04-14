<?php

/** Archived events controller
*
* This controller serves to produce an events archive for the Portable Antiquities Scheme.
* Formats include JSON, XML, ATOM, RSS
* 
* @category		Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) 2011 Daniel Pett
* @license		GNU General Public License
* @author		Daniel Pett
* @version		1
* @since		23 September 2011
*/
class Events_ArchivesController extends Pas_Controller_Action_Admin {

	protected $_contextSwitch;
	
    protected $_contexts = array('xml','json','atom','rss');
	
    /**
	* Initialise the ACL for access levels and the contexts
	*/
	public function init() {
	$this->_helper->acl->allow('public',null);
	
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	
	$this->_contextSwitch = $this->_helper->contextSwitch();
	
	$this->_contextSwitch->setAutoJsonSerialization(false);
	
	$this->_contextSwitch->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', $this->_contexts)
		->addActionContext('upcoming', $this->_contexts)
		->addActionContext('event',$this->_contexts)
		->initContext();
	}
	
	/** Return data for the index page of the archive
	* @access public
	* @todo Clean up the atom output 
	*/	
	public function indexAction() {
	$events = new Events();
	$events = $events->getArchivedEventsList($this->_getAllParams());
	$current_year = date('Y');
	$years = range(1998, $current_year);
	$yearslist = array();
	foreach($years as $key => $value) {
	$yearslist[] = array('year' => $value);
	}
	$list = $yearslist;
	$this->view->years = $list;
	$this->view->events = $events;
	}
	
	/** Return data for the archive by years
	*/
	public function yearAction() {
	$date = $this->_getParam('date').'-01-01' ? $this->_getParam('date') 
	. '-01-01': Zend_Date::now()->toString('yyyy-MM-dd'); 
	$this->view->date = substr($date,0,4);
	$current_year = date('Y');
	$years = range(1998, $current_year);
	$yearslist = array();
	foreach($years as $key => $value) {
	$yearslist[] = array('year' => $value);
	}
	$list = $yearslist;
	$this->view->years = $list;
	$calendar= new Calendar($date); 
	$cases = new Events();
	$cases = $cases->getEventsByDayPast();
	$lists = array();
	foreach ($cases as $value) {
	$lists[] = $value['eventStartDate'];
	}
	$caseslisted = $lists;
	$calendar->highlighted_dates = $caseslisted;
	$url = $this->view->url(array(
		'module' => 'events', 
		'controller' => 'archives',
		'action' => 'list'),null,true);
	$calendar->formatted_link_to = $url . '/day/%Y-%m-%d';
//	print '<div id="calendar">';
//	print("<ul id=\"year\">\n"); 
	for($i=1;$i<=12;$i++){ 
//		print("<li>"); 
		if( $i == $calendar->month ){ 
			print($calendar->output_calendar(null,null, 'table table-striped')); 
		} else { 
			print($calendar->output_calendar($calendar->year, $i, 'table table-striped')); 
		} 
//		print("</li>\n"); 
	} 
//	print("</ul>");
	print("</div>"); 
	}
	
	/** Return data for the list page of archived events
	* @uses Events.php
	* @throws Exception
	*/
	public function listAction() {
	if($this->_getParam('day',false)){
	$this->view->day = $this->_getParam('day');
	$this->view->headTitle('List of events for ' . $this->_getParam('day'));
	$events = new Events();
	$this->view->events = $events->getEventsDate($this->_getParam('day'), array('page' => $this->_getParam('page')));
	} else {
	throw new Pas_Exception_Param('No date has been entered');
	}
	} 
	
	}