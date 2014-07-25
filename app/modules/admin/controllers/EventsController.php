<?php
/** Controller for setting up and manipulating staff contacts
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Pas_Service_Geo_Coder
 * @uses Events
 * @uses EventForm
 * 
 */
class Admin_EventsController extends Pas_Controller_Action_Admin {

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $higherLevel = array('admin','flos');

    /** The research roles
     * @access protected
     * @var array
     */
    protected $researchLevel = array('member','heros','research');

    /** The restricted roles
     * @access protected
     * @var array
     */
    protected $restricted = array('public', null);
    
    /** The events model
     * @access protected
     * @var \Events
     */
    protected $_events;
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(
                array('fa', 'flos'), 
                array('add', 'edit', 'delete', 'index')
                );
        $this->_helper->_acl->allow('admin', null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_geocoder = new Pas_Service_Geo_Coder();
        $this->_events = new Events();
    }
    
    /** Set up index of events
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->events = $this->_events->getEventsAdmin($this->_getParam('page'));
    }
    
    /** Add an event
     * @access public
     * @return void
     * @todo geocoding and processing in view
     */
    public function addAction() {
        $form = new EventForm();
        $form->details->setLegend('Add a new event');
        $form->submit->setLabel('Save event');
        $this->view->form = $form;
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $this->_events->insert($form->getValues());
            $this->_flashMessenger->addMessage('New event created!');
            $this->_redirect('/admin/events/');
        } else {
            $form->populate($this->_request->getPost());
        }
    }
    
    /** Edit an event
     * @access public
     * @return void
     * @todo Add geocoding in model
    */
    public function editAction() {
        $form = new EventForm();
        $form->details->setLegend('Edit event');
        $form->submit->setLabel('Save event');
        $this->view->form = $form;
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $where = array();
            $where[] = $this->_events->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
            $this->_events->update($form->getValues(),$where);
            $this->_flashMessenger->addMessage(
                    'You updated: <em>' 
                    . $form->getValue('eventTitle')
                    . '</em> successfully.');
            $this->_redirect('/admin/events/');
        } else {
            $form->populate($this->_request->getPost());
        }
    }
    /** Delete an event
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $this->_flashMessenger->addMessage('No changes implemented.');
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'ID = ' . $id;
                $this->_events->delete($where);
                $this->_flashMessenger->addMessage('Event information deleted! This cannot be undone.');
            }
            $this->_redirect('/admin/events/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
             $this->view->event = $this->_events->fetchRow('id =' . $id);
            }
        }
    }
}