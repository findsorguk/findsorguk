<?php
/** Controller for setting up and manipulating staff contacts
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_EventsController extends Pas_Controller_Action_Admin {

	protected $higherLevel = array('admin','flos');

	protected $researchLevel = array('member','heros','research');

	protected $restricted = array('public');

	/** Set up the ACL and contexts
	*/
	public function init() {
	$flosActions = array('index',);
	$faActions = array('add','edit','delete','index');
	$this->_helper->_acl->allow('flos',$faActions);
	$this->_helper->_acl->allow('fa',$faActions);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_config = Zend_Registry::get('config');
	$this->_geocoder = new Pas_Service_Geo_Coder();
	}
	/** Set up index of events
	*/
	public function indexAction() {
	$eventsList = new Events();
	$this->view->events = $eventsList->getEventsAdmin($this->_getParam('page'));
	}
	/** Add an event
	*/
	public function addAction() {
	$form = new EventForm();
	$form->details->setLegend('Add a new event');
	$form->submit->setLabel('Save event');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('eventLocation');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$pm = new Pas_Service_Geo_Geoplanet($this->_helper->config()->webservice->ydnkeys->appid);
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$insertdata = array(
	'eventTitle' => $form->getValue('eventTitle'),
	'eventDescription' => $form->getValue('eventDescription'),
	'eventLocation' => $form->getValue('eventLocation'),
	'eventStartTime' => $form->getValue('eventStartTime'),
	'eventEndTime' => $form->getValue('eventEndTime'),
	'eventStartDate' => $form->getValue('eventStartDate'),
	'eventEndDate' => $form->getValue('eventEndDate'),
	'eventRegion' => $form->getValue('eventRegion'),
	'adultsAttend' => $form->getValue('adultsAttend'),
	'childrenAttend' => $form->getValue('childrenAttend'),
	'organisation' => $form->getValue('organisation'),
	'eventType' => $form->getValue('eventType'),
	'latitude' => $lat,
	'longitude' => $lon,
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
		foreach ($insertdata as $key => $value) {
		  if (is_null($value) || $value=="") {
			unset($insertdata[$key]);
		  }
		}
	$events = new Events();
	$events->insert($insertdata);
	$this->_flashMessenger->addMessage('New event created!');
	$this->_redirect('/admin/events/');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit an event
	*/
	public function editAction() {
	$form = new EventForm();
	$form->details->setLegend('Edit event');
	$form->submit->setLabel('Save event');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('eventLocation');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$lon = $coords['lon'];
		$pm = new Pas_Service_Geo_Geoplanet($this->_helper->config()->webservice->ydnkeys->appid);
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$insertdata = array(
	'eventTitle' => $form->getValue('eventTitle'),
	'eventDescription' => $form->getValue('eventDescription'),
	'eventLocation' => $form->getValue('eventLocation'),
	'organisation' => $form->getValue('organisation'),
	'eventStartTime' => $form->getValue('eventStartTime'),
	'eventEndTime' => $form->getValue('eventEndTime'),
	'eventStartDate' => $form->getValue('eventStartDate'),
	'eventEndDate' => $form->getValue('eventEndDate'),
	'eventRegion' => $form->getValue('eventRegion'),
	'eventType' => $form->getValue('eventType'),
	'latitude' => $lat,
	'longitude' => $lon,
	'adultsAttend' => $form->getValue('adultsAttend'),
	'childrenAttend' => $form->getValue('childrenAttend'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
		foreach ($insertdata as $key => $value) {
		  if (is_null($value) || $value=="") {
			unset($insertdata[$key]);
		  }
		}
	$events = new Events();
	$where = array();
	$where[] = $events->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$events->update($insertdata,$where);
	$this->_flashMessenger->addMessage('You updated: <em>' . $form->getValue('eventTitle')
	. '</em> successfully.');
	$this->_redirect('/admin/events/');
	} else {
	$form->populate($formData);
	}
	}  else {
	$id = (int)$this->_getParam('id', 0);
	if ($id > 0) {
	$events = new Events();
	$event = $events->fetchRow('id='.(int)$id);
	$form->populate($event->toArray());
	}
	}
	}
	/** Delete an event
	*/
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$this->_flashMessenger->addMessage('No changes implemented.');
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$events = new Events();
	$where = 'ID = ' . $id;
	$events->delete($where);
	$this->_flashMessenger->addMessage('Event information deleted! This cannot be undone.');
	}
	$this->_redirect('/admin/events/');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$events = new Events();
	$this->view->event = $events->fetchRow('id =' . $id);
	}
	}
	}
}