<?php
/** Controller for displaying Roman articles within the coin guide
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_CalendarController extends Pas_Controller_Action_Admin {
	
	protected $_gcal;
	
	/** Set up the ACL and contexts
	*/	
	public function init() {	
		$this->_helper->_acl->allow('flos',NULL);
	    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	    $this->_gcal = new Pas_Calendar_Mapper();
    }
	
	/** Display index pages for the individual
	*/	
	public function indexAction() {	
	    $this->view->eventFeed = $this->_gcal->getEventFeed();
	}
	
	public function eventAction()
	{
		$this->view->event = $this->_gcal->getEvent($this->_getParam('id'));
		
	}
	
	public function addAction()
	{
		$form = new CalendarForm();
		$form->details->setLegend('Add a new event');
		$this->view->form = $form;
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {
		$formData['creator'] = $this->_helper->identity->getPerson()->fullname;
		$insert = $this->_gcal->addEvent($formData);
		$this->_flashMessenger->addMessage('New calendar event added');
		$this->_redirect('/users/calendar/');
		} else {
		$form->populate($formData);
		}
		}
	}	
	
	public function editAction()
	{
		$form = new CalendarForm();
		$form->details->setLegend('Edit an event');
		$this->view->form = $form;
		$event = $this->_gcal->getEventById($this->_getParam('id'));
		$eventData = array(
		'title' 	=> $event->title,
		'id' 		=> substr($event->id,strrpos($event->id,'/')+1,26),
		'startTime' 	=> date('G:i', strtotime($event->when[0]->startTime)),
		'endTime'   	=> date('G:i', strtotime($event->when[0]->endTime)),
		'startDate' 	=> date('Y-m-d', strtotime($event->when[0]->startTime)),
		'endDate'   	=> date('Y-m-d', strtotime($event->when[0]->startTime)),
		'location' 	=> $event->where[0],
		'updated' 	=> $event->updated,
		'content' 	=> $event->content,
		'type'		=> $event->extendedProperty[0],
		'creator'	=> $event->extendedProperty[1],
		);
		
		$form->populate($eventData);
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {
		$formData['id'] = $this->_getParam('id');
		$formData['creator'] = $this->_helper->identity->getPerson()->fullname;
		$edit = $this->_gcal->editEvent($formData);
		$this->_flashMessenger->addMessage('Calendar event updated');
		$this->_redirect('/users/calendar/');
		} else {
		$form->populate($formData);
		}
		}
	}
	
	public function deleteAction()
	{
		$event = $this->_gcal->getEventById($this->_getParam('id'));
		$this->view->id = substr($event->id,strrpos($event->id,'/')+1,26);
		$this->view->title = $event->title;
		if ($this->_request->isPost()) {
		$id = $this->_request->getPost('id');
		$del = $this->_request->getPost('del');
		
		if ($del == 'Yes' && $id == substr($event->id,strrpos($event->id,'/')+1,26)) {
		$event->delete();
		}
		$this->_flashMessenger->addMessage('That event has been deleted');
		$this->_redirect('/users/calendar');
		
		}
	}
}