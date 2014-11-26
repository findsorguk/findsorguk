<?php

/** Controller for displaying Roman articles within the coin guide
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Pas_Calendar_Mapper
 * @uses CalendarForm
 */
class Users_CalendarController extends Pas_Controller_Action_Admin
{

    /** The calendar class
     * @access protected
     * @var \Pas_Calendar_Mapper
     */
    protected $_gcal;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('flos', null);
        $this->_gcal = new Pas_Calendar_Mapper();

    }

    /** Display index pages for the individual
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->eventFeed = $this->_gcal->getEventFeed();
    }

    /** the event details
     * @access public
     * @return void
     */
    public function eventAction()
    {
        $this->view->event = $this->_gcal->getEvent($this->_getParam('id'));
    }

    /** Add an event to gcal
     * @access public
     * @return void
     */
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
                $this->getFlash()->addMessage('New calendar event added');
                $this->redirect('/users/calendar/');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit a calendar event
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new CalendarForm();
        $form->details->setLegend('Edit an event');
        $this->view->form = $form;
        $event = $this->_gcal->getEvent($this->_getParam('id'));
        $eventData = array(
            'title' => $event->title,
            'id' => substr($event->id, strrpos($event->id, '/') + 1, 26),
            'startTime' => date('G:i', strtotime($event->when[0]->startTime)),
            'endTime' => date('G:i', strtotime($event->when[0]->endTime)),
            'startDate' => date('Y-m-d', strtotime($event->when[0]->startTime)),
            'endDate' => date('Y-m-d', strtotime($event->when[0]->startTime)),
            'location' => $event->where[0],
            'updated' => $event->updated,
            'content' => $event->content,
            'type' => $event->extendedProperty[0],
            'creator' => $event->extendedProperty[1],
        );
        $form->populate($eventData);
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $formData['id'] = $this->_getParam('id');
                $formData['creator'] = $this->_helper->identity->getPerson()->fullname;
                $edit = $this->_gcal->editEvent($formData);
                $this->getFlash()->addMessage('Calendar event updated');
                $this->redirect('/users/calendar/');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Delete an event from gcal
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        $event = $this->_gcal->getEvent($this->_getParam('id'));
        $this->view->id = substr($event->id, strrpos($event->id, '/') + 1, 26);
        $this->view->title = $event->title;
        if ($this->_request->isPost()) {
            $id = $this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            //Check if delete parameter is set and that the string is correct
            if ($del == 'Yes' && $id == substr($event->id, strrpos($event->id, '/') + 1, 26)) {
                $event->delete();
            }
            $this->getFlash()->addMessage('That event has been deleted');
            $this->xredirect('/users/calendar');
        }
    }
}