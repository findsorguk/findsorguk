<?php
/** The upcoming events controller for the events package
*
* @category   	Pas
* @package    	Pas_Controller
* @subpackage 	ActionAdmin
* @copyright	Copyright (c) 2011 Daniel Pett
* @license		GNU General Public License
* @author		Daniel Pett
*/

class Events_UpcomingController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL for access levels and the context switches
	*/

	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$contexts = array('xml','json','ics');
	$contextsindex = array('xml','json','rss','atom');
	$contextSwitch = $this->_helper->contextSwitch();
	$contextSwitch->setAutoDisableLayout(true)
		->addContext('ics',array('suffix' => 'ics'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', $contextsindex)
		->addActionContext('upcoming', $contextsindex)
		->addActionContext('event',$contexts)
		->addActionContext('list',array('xml','json'))
		->initContext();
	}

	/** Render data for view on index action
	*/	
	public function indexAction() {
	$format = $this->_getParam('format');
	if(!isset($format)){
	$eventsList = new Events();
	$eventsListed = $eventsList->getUpcomingEvents();
	$calendar = new Calendar;
	$lists = array();
	foreach ($eventsListed as $value) {
	$lists[] = $value['eventStartDate'];
	}
	$listedDates = $lists;
	$calendar->highlighted_dates = $listedDates;

	$first_day = $calendar->year . "-" . $calendar->month . "-01";
	$prev_year = date("Y", strtotime("+2 month", strtotime($first_day)));
	$prev_month = date("m", strtotime("+2 month", strtotime($first_day)));
	$next_year = date("Y", strtotime("+1 month", strtotime($first_day)));
	$next_month = date("m", strtotime("+1 month", strtotime($first_day)));
	$url = $this->view->url(array('module' => 'events','controller' => 'upcoming','action' => 'index'),'upcoming',true);
	$calendar->formatted_link_to = $url.'/%Y-%m-%d';
	$this->view->curr = $calendar->output_calendar(null,null,'table table-striped');
	$this->view->nextplusone = $calendar->output_calendar($prev_year, $prev_month,'table table-striped');
	$this->view->next =  $calendar->output_calendar($next_year, $next_month,'table table-striped');
	$events = new Events();
	if($this->_getParam('date') == NULL) {
	$this->view->events = $eventsList->getUpcomingEventsList();
	$this->view->eventdate = Zend_Date::now()->toString('yyyy-MM-dd');
	} else {
	$this->view->events = $events->getEventbyDay($this->_getParam('date'));
	$this->view->eventdate =$this->_getParam('date');
	}
	} else if($format == 'xml' || $format == 'json') {
	$this->_helper->layout->disableLayout(); 
	$events = new Events();
	$this->view->events = $events->getUpcomingEventsList();
	}  else if( in_array($format,array('rss','atom')) ? $format : 'rss') {
	$events = new Events();
	$events = $events->getUpcomingEventsList();
	$feedArray = array(
			'title' => 'Finds from the Scheme',
			'link' => $this->view->CurUrl(),
			'charset' => 'utf-8',
			'description' => 'The latest Portable Antiquities Scheme events',
			'author' => 'The Portable Antiquities Scheme',
			'image' => Zend_Registry::get('siteurl').'/images/logos/pas.gif',
			'email' => 'info@finds.org.uk',
			'copyright' => 'Creative Commons Licenced',
			'generator' => 'The Scheme database powered by Zend Framework and Dan\'s magic',
			'language' => 'en',
			'entries' => array()
		);
	foreach ($events as $event) {
	$feedArray['entries'][] = array(
				'title' => $event['eventTitle'],
				'link' => Zend_Registry::get('siteurl').'/events/info/index/id/'.$event['id'],
				'guid' => Zend_Registry::get('siteurl').'/events/info/index/id/'.$event['id'],
				'description' => strip_tags($event['eventDescription']),
				'lastUpdate' => strtotime($event['updated']),
				'content' => $event['eventDescription'],
				'georss'=> $event['latitude'].','.$event['longitude'],  
				//'enclosure' => array()
	);
	}
	$feed = Zend_Feed::importArray($feedArray, $format);
	$feed->send();
	}
	}

	/**
	* Render data for view on list action
	*/	
	public function listAction() {
	if($this->_getParam('day',false)){
	$this->view->day = $this->_getParam('day');
	$events = new Events();
	$this->view->events = $events->getEventsDate($this->_getParam('day'));
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	} 

	/**
	* Render data for view on year archive action
	*/	
	public function yearAction() {
	$date = $this->_getParam('date').'-01-01' ? $this->_getParam('date').'-01-01': Zend_Date::now()->toString('yyyy-MM-dd'); 
	$current_year = date('Y');
	if($date < $current_year) {
	$this->_flashMessenger->addMessage('As you chose a date in the past, you have been sent to the archive');
	$this->_redirect('events/archives/year/date/'.$date);
	}
	$years = range($current_year, $current_year + 2);
	$yearslist = array();
	foreach($years as $key => $value) {
	$yearslist[] = array('year' => $value);
	}
	$list = $yearslist;
	$this->view->years = $list;
	$calendar= new Calendar($date); 
	$cases = new Events();
	$cases = $cases->getEventsByDayFuture();
	$lists = array();
	foreach ($cases as $value) {
	$lists[] = $value['eventStartDate'];
	}
	$caseslisted = $lists;
	$calendar->highlighted_dates = $caseslisted;
	$url = $this->view->url(array('module' => 'events','controller' => 'upcoming','action' => 'list'),null,true);
	$calendar->formatted_link_to = $url.'/day/%Y-%m-%d';
	print '<div id="calendar">';
	print("<ul id=\"year\">\n"); 
	for($i=1;$i<=12;$i++){ 
		print("<li>"); 
		if( $i == $calendar->month ){ 
			print($calendar->output_calendar()); 
		} else { 
			print($calendar->output_calendar($calendar->year, $i)); 
		} 
		print("</li>\n"); 
	} 
	print("</ul></div>"); 
	}

	/**
	* Render data for view on map action
	*/	
	public function mapAction() {
	$this->view->inlineScript()->appendFile("http://maps.google.com/maps/api/js?sensor=false",$type="text/javascript");
	}

}