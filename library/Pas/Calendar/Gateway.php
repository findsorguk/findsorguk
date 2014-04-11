<?php
/** This class is a mapping gateway to retrieving data from the Google Calendar
 * @version 1
 * @since 	21/11/2012
 * @author 	Daniel Pett
 * @license 
 * @copyright Daniel Pett/British Museum
 * @package Pas_Calendar
 * @subpackage Mapper
 */
class Pas_Calendar_Gateway 
{

	protected $_username;
	protected $_password;
	protected $_config;
	protected $_service;
	protected $_calendar;
	protected $_timezone = 'T00:00:00-00:00';
	protected $_today;
	protected $_tonight;
	
	public function __construct()
	{
		$this->_config = Zend_Registry::get('config');
	    $this->_username = $this->_config->webservice->google->username;
	    $this->_password = $this->_config->webservice->google->password;
	    $this->_service  = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
	}
	
	public function _setToday($today)
	{
		if(!isset($today)){
		$this->_today = date('Y-m-d');
		} else {
			$this->_today = $today;
		}	
	}
	
	public function _setTonight($tonight)
	{
		if(isset($tonight)){
		$this->_tonight = date('Y-m-d' . strtotime($this->_today * 24*60*60)) . $this->_timezone;
		} else {
			$this->_tonight = $tonight;
		}
	}
	
	public function _getToday()
	{
		return $this->_today;
	}
	
	public function _getTonight()
	{
		return $this->_tonight;
	}	
	
	/** set up the client instance
	 * @access public
	 * @return Zend_Gdata_Calendar
	 */
	public function getCalendar(){
		$client = Zend_Gdata_ClientLogin::getHttpClient(
			$this->_username, $this->_password, $this->_service
			);
		return new Zend_Gdata_Calendar($client);	
	}
	
	
	/** A function to return list of calendars for an account
	 * @access public
	 * @return Zend_Gdata_Calendar_ListFeed
	 */
	public function getCalendarList()
	{
    	return $this->getCalendar()->getCalendarListFeed();
	}
	
	/** Get a calendar event by it's id number
	 * @access public
	 * @param $id
	 * @return Zend_Gdata_Calendar_EventEntry
	 */
	public function getEvent($id)
	{
		$query = $this->getCalendar()->newEventQuery();
		$query->setUser('default');
		$query->setVisibility('private');
		$query->setProjection('full');
		$query->setEvent($id);
		return $this->getCalendar()->getCalendarEventEntry($query);
	}
	
	/** Get the event feed
	 * @param $max integer
	 * @access public
	 * @return Zend_Gdata_Calendar_EventFeed
	 */
	public function getEventFeed( 
		$max = 300, $sortOrder = 'a', $user = 'default', 
		$visiblity = 'private', $projection = 'full', $future = 'true',
		$orderBy = 'starttime', $startTime = NULL,$endTime = NULL, $timeZone = NULL, 
		$fullTextQuery = NULL  )
	{
		$query = $this->getCalendar()->newEventQuery();
		//Preset options
		$query->setUser( $user );
		$query->setVisibility( $visiblity );
		$query->setProjection( $projection );
		$query->setOrderby( $startTime );
		$query->setFutureevents( $future );
		$query->setMaxResults( $max );
		$query->setSortOrder( $sortOrder );
		
		//Set the optional conditions
		if(!is_null($startTime)){
			$query->setStartMin($startTime);
		}
		if(!is_null($endTime)){
			$query->setStartMax($endTime);
		}
		if(!is_null($fullTextQuery)){
			$query->setQuery($fullTextQuery);
		}
		
		return $this->getCalendar()->getCalendarEventFeed($query);
	}
	
	/** Get the extended properties for an event
	 * @access public
	 * @param $event
	 */
	public function getExtendedProperty( Zend_Gdata_Calendar_EventEntry $event)
	{
		//Get the extend properties
		$extendedProperties = $event->extendedProperty();
		foreach ( $extendedProperties as $extendedProperty){
				return $extendedProperty->value;
			}
	}

	/** Add extended property to event
	 * @access public
	 * @param $event
	 * @param array $properties
	 * @return Zend_Gdata_Calendar_EventEntry
	 */
	private function addExtendedProperty( Zend_Gdata_Calendar_EventEntry $event, $properties)
	{
		//Check if properties in an array
		if(is_array($properties)){
		//Set up the array of extended properties
		$extendedProperty = array();
		//For each array component add the extended property
		foreach($properties as $key => $value){
		$extendedProperty[] = $this->getCalendar()->newExtendedProperty( $key, $value);
		}
		//Merge the extended properties
		$extendedProperties = array_merge($event->extendedProperty, $extendedProperty);
		$event->extendedProperty = $extendedProperties;
		//Save the event and rewrite it on the calendar
		$eventNew = $event->save();
		return $eventNew;
		} else {
			throw new Pas_Calendar_Exception('The properties offered for extension are not an array', 500);
		}
	}
	
	
	public function addEvent($data)
	{
		if(is_array($data)){
		$event= $this->getCalendar()->newEventEntry();
		$event->title = $this->getCalendar()->newTitle($data['title']);
		$event->where = array($this->getCalendar()->newWhere($data['location']));
		$event->content = $this->getCalendar()->newContent($data['content']);
		 
		// Set the date using RFC 3339 format.
		$startDate 	= $data['startDate'];
		$startTime 	= $data['startTime'];
		$endDate 	= $data['endDate'];
		$endTime 	= $data['endTime'];
		$tzOffset = "-00";
		 
		$when = $this->getCalendar()->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
		$event->when = array($when);
		// Upload the event to the calendar server
		// A copy of the event as it is recorded on the server is returned
		$newEvent = $this->getCalendar()->insertEvent($event);
		//Create extended properties
		$properties = array('type' => $data['eventType'], 'creator' => $data['creator']);
		$this->addExtendedProperty($newEvent, $properties);
		
		} else {
			throw new Pas_Calendar_Exception('The data supplied is not an array', 500);
		}
	}
	
	public function editEvent($data)
	{
		if(is_array($data)){
		//retrieve the event object from the calendar
		$event= $this->getEvent($data['id']);
		//Set the new attributes
		$event->title = $this->getCalendar()->newTitle($data['title']);
		$event->where = array($this->getCalendar()->newWhere($data['location']));
		$event->content = $this->getCalendar()->newContent($data['content']);
		// Set the when date using RFC 3339 format.
		$startDate 	= $data['startDate'];
		$startTime 	= $data['startTime'];
		$endDate 	= $data['endDate'];
		$endTime 	= $data['endTime'];
		$tzOffset = "-00";
		$when = $this->getCalendar()->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
		$event->when = array($when);
		//The next two code lines add extended properties and save the event
		$properties = array('type' => $data['eventType'], 'creator' => $data['creator']);
		//As we are calling add extended properties, you don't need a save
		$this->addExtendedProperty($event, $properties);
		//If you don't use the extended properties you need to uncomment the line below 
		//$event->save();
		} else {
			throw new Pas_Calendar_Exception('The data supplied is not an array', 500);
		}
	}
}