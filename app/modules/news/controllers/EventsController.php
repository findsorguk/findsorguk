<?php

/**  Controller for PAS events
 *
 * This has been revised on the 12th June 2014
 *
 * @category     Pas
 * @package      Pas_Controller_Action
 * @subpackage    Admin
 * @license    GNU General Public License
 * @author       Daniel Pett <dpett@britishmuseum.org>
 * @copyright    Daniel Pett 2011 <dpett@britishmuseum.org>
 * @since        23 Sept. 2011
 * @version      2
 * @uses Events
 * @uses Content
 * @uses Pas_Exception_Param
 */
class News_EventsController extends Pas_Controller_Action_Admin
{

    /** The events model
     * @access protected
     * @var \Events
     */
    protected $_events;

    /** Initialise the ACL for access levels and set up contexts
     */
    public function init()
    {

        $this->_helper->acl->allow('public', null);
        $contexts = array('xml', 'json', 'rss', 'atom');
        $contextSwitch = $this->_helper->contextSwitch()
            ->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch();
        $contextSwitch->setAutoDisableLayout(true)
            ->addContext('rss', array('suffix' => 'rss'))
            ->addContext('atom', array('suffix' => 'atom'))
            ->addActionContext('upcoming', $contexts)
            ->addActionContext('archive', $contexts)
            ->addActionContext('event', array('xml', 'json'))
            ->initContext();
        $this->_events = new Events();
    }

    /** Render data for view on index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('events');
    }

    /** Render data for view on map action
     * @access public
     * @return void
     */
    public function mapAction()
    {
        //All magic happens in view.
    }

    /** Render data for upcoming events
     * @access public
     * @return void
     */
    public function upcomingAction()
    {
        $this->view->events = $this->_events->getUpcomingEvents();
    }

    /** Render data for view on index action
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    function detailsAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->events = $this->_events->getEventData($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Archive page
     * @access public
     * @return void
     */
    public function archiveAction()
    {
        $this->view->events = $this->_events->getArchivedEventsList($this->getAllParams());
    }
}