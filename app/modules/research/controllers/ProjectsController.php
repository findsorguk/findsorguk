<?php

/** Controller for all getting research projects out of system
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses ResearchProjects
 * @uses SuggestedResearch
 * @uses Pas_Exception_Param
 *
 */
class Research_ProjectsController extends Pas_Controller_Action_Admin
{

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $higherLevel = array('admin', 'flos');

    /** The research level array
     * @access protected
     * @var array
     */
    protected $researchLevel = array('member', 'heros', 'research');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $restricted = array('public', null);

    /** The research projects model */
    protected $_researchProjects;

    /**
     * @return mixed
     */
    public function getResearchProjects()
    {
        $this->_researchProjects = new ResearchProjects();
        return $this->_researchProjects;
    }


    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()
            ->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addContext('rss', array('suffix' => 'rss'))
            ->addContext('atom', array('suffix' => 'atom'))
            ->addActionContext('project', array('xml', 'json'))
            ->addActionContext('topic', array('xml', 'json'))
            ->addActionContext('suggested', array('xml', 'json', 'rss', 'atom'))
            ->addActionContext('index', array('xml', 'json', 'rss', 'atom'))
            ->initContext();
    }

    /** Set up index pages
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->projects = $this->getResearchProjects()->getAllProjects($this->getAllParams());
    }

    /** Get an individual project
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function projectAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->projects = $this->getResearchProjects()->getProjectDetails($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter);
        }
    }

    /** List of suggested topics
     * @access public
     * @return void
     */
    public function suggestedAction()
    {
        $projects = new SuggestedResearch();
        if (in_array($this->_helper->contextSwitch->getCurrentContext(),
            array('xml', 'json', 'rss', 'atom'))) {
            $this->view->suggested = $projects->getAll($this->getAllParams(), 0);
        } else {
            $this->view->undergrad = $projects->getTopicByType(1);
            $this->view->masters = $projects->getTopicByType(2);
            $this->view->phd = $projects->getTopicByType(3);
        }
    }

    /** Get an individual topic
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function topicAction()
    {
        if ($this->getParam('id', false)) {
            $topic = new SuggestedResearch();
            $this->view->topic = $topic->getTopic($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}