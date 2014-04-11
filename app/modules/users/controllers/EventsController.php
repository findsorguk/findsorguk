<?php
/** Controller for managing events data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_EventsController extends Pas_Controller_Action_Admin {

	protected $_gmapskey,$_config,$_geocoder;
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('flos',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl()
	. '/js/JQuery/ui.datepicker.js', $type='text/javascript');
	$this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/ui.datepicker.css');
	$this->_geocoder = new Pas_Service_Geo_Coder();
    }
	/** List a paginated events data set
	*/
	public function indexAction() {
	$eventsList = new Events();
	$this->view->events = $eventsList->getEventsAdmin($this->_getParam('page'));
	}
	/** Add a new event
	*/
	public function addAction(){
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
		$long = $coords['lon'];
	} else {
		$lat = NULL;
		$lon = NULL;
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
	'longitude' => $long,
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
	$this->_redirect('/users/events/');
	}  else  {
	$form->populate($formData);
	}
	}
	}
	/** Edit event details
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
		$long = $coords['lon'];
	} else {
		$lat = NULL;
		$lon = NULL;
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
	'longitude' => $long,
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
	$this->_redirect('/users/events/');
	} else  {
	$form->populate($formData);
	}
	}  else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$events = new Events();
	$event = $events->fetchRow('id=' . (int)$id);
	$form->populate($event->toArray());
	}
	}
	}
	/** Delete event details
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
	$this->_redirect('/users/events/');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$events = new Events();
	$this->view->event = $events->fetchRow('id ='.$id);
	}
	}
	}
}