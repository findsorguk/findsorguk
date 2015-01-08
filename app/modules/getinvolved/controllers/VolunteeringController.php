<?php

/** Controller for getting information on volunteer roles
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Volunteers
 * @uses Pas_Exception_Param
 */
class GetInvolved_VolunteeringController extends Pas_Controller_Action_Admin
{

    /** The volunteers model
     * @access protected
     * @var \Volunteers
     */
    protected $_volunteers;

    /** Initialise the ACL and set up contexts
     * @access public
     * @return void
     */
    public function init()
    {

        $this->_helper->acl->allow('public', null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addContext('rss', array('suffix' => 'rss'))
            ->addContext('atom', array('suffix' => 'atom'))
            ->addActionContext('index', array('xml', 'json', 'rss', 'atom'))
            ->addActionContext('role', array('xml', 'json'))
            ->initContext();
        $this->_volunteers = new Volunteers();
    }

    /** Render the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->vols = $this->_volunteers->getCurrentOpps($this->getAllParams());
    }

    /** Render individual role
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function roleAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->vols = $this->_volunteers->getOppDetails($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}