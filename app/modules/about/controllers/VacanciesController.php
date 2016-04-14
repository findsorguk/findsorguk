<?php

/** Controller for current and archived vacancies
 *
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Vacancies
 * @uses Pas_Exception_Param
 */
class About_VacanciesController extends Pas_Controller_Action_Admin
{

    /** The vacancies model
     * @access protected
     * @var \Vacancies
     */
    protected $_vacancies;

    /** Initialise the ACL and contexts
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
            ->addActionContext('archives', array('xml', 'json'))
            ->addActionContext('vacancy', array('xml', 'json'))
            ->initContext();
        $this->_vacancies = new Vacancies();

    }

    /** Render the index pages and rss
     * @access public
     */
    public function indexAction()
    {
        $this->view->vacs = $this->_vacancies->getLiveJobs($this->getParam('page'));
    }

    /** Render the archives section
     * @access public
     */
    public function archivesAction()
    {
        $this->view->archives = $this->_vacancies->getArchiveJobs($this->getParam('page'));
    }

    /** Render a vacancy's details
     * @access public
     * @throws Pas_Exception_Param if missing parameter on URL.
     */
    public function vacancyAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->vacs = $this->_vacancies->getJobDetails($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}