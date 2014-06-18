<?php

/** 
 * Controller for PAS events
 * 
 * This has been revised on the 12th June 2014
 *
 * @category     Pas
 * @package      Pas_Controller_Action
 * @subpackage	Admin
 * @license	GNU General Public License
 * @author       Daniel Pett <dpett@britishmuseum.org>
 * @copyright    Daniel Pett 2011 <dpett@britishmuseum.org>
 * @since        23 Sept. 2011
 * @version      2
*/
class News_EventsController extends Pas_Controller_Action_Admin {

    protected $_events;
    
    /** Initialise the ACL for access levels and set up contexts
    */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow('public',null);
        $contexts = array('xml','json', 'rss', 'atom');
        $contextSwitch = $this->_helper->contextSwitch()
                ->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch();
        $contextSwitch->setAutoDisableLayout(true)
                ->addContext('rss',array('suffix' => 'rss'))
                ->addContext('atom',array('suffix' => 'atom'))
                ->addActionContext('upcoming', $contexts)
                ->addActionContext('archive', $contexts)
                ->addActionContext('event',array('xml','json'))
                ->initContext();
        $this->_events = new Events();
    }

    /**
     * Render data for view on index action
     * @access public
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('events');
    }
    
    /**
     * Render data for view on map action
     * @access public
     */	
    public function mapAction() {
        //All magic happens in view.
    }

    /** 
     * Render data for upcoming events
     * @access public
     */	
    public function upcomingAction() {
        $this->view->events = $this->_events->getUpcomingEvents();
     }

    /**
    * Render data for view on index action
    */	
    function detailsAction() {
        $this->view->events = $this->_events->getEventData($this->_getParam('id'));
    }

    /** Archive page
    * @access public
    */	
    public function archiveAction() {
        $this->view->events = $this->_events->getArchivedEventsList($this->_getAllParams());
    }
}