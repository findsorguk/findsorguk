<?php
/** Controller for all getting research projects out of system
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Research_ProjectsController extends Pas_Controller_Action_Admin {

	protected $higherLevel = array('admin','flos');

	protected $researchLevel = array('member','heros','research');

	protected $restricted = array('public');

	/** Initialise the ACL and contexts
	*/
	public function init() {
 	$this->_helper->_acl->allow(null);
               $this->_helper->contextSwitch()
                ->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
                ->setAutoDisableLayout(true)
                ->addContext('rss',array('suffix' => 'rss'))
                ->addContext('atom',array('suffix' => 'atom'))
                ->addActionContext('project',array('xml','json'))
                ->addActionContext('topic',array('xml','json'))
                ->addActionContext('suggested',array('xml','json','rss','atom'))
                ->addActionContext('index', array('xml','json','rss','atom'))
                ->initContext();

	}

	/** Set up index pages
	*/
	public function indexAction() {

	$projects = new ResearchProjects();
	$this->view->projects = $projects->getAllProjects($this->_getAllParams());
	}

	/** get an individual project
	*/
	public function projectAction(){
	if($this->_getParam('id',false)){
		$projects = new ResearchProjects();
		$this->view->projects = $projects->getProjectDetails($this->_getParam('id'));
	} else {
		throw new Exception($this->_missingParameter);
	}
    }

    /** List of suggested topics
	*/
	public function suggestedAction() {
        $projects = new Suggested();
	if(in_array($this->_helper->contextSwitch->getCurrentContext(),array('xml','json','rss','atom'))) {
	$this->view->suggested = $projects->getAll($this->_getAllParams(),0);
	} else {
        $this->view->undergrad = $projects->getTopicByType(1);
	$this->view->masters = $projects->getTopicByType(2);
	$this->view->phd = $projects->getTopicByType(3);
	}
	}

	/** Get an individual topic
	*/
	public function topicAction() {
	if($this->_getParam('id',false)){
		$topic = new Suggested();
		$this->view->topic = $topic->getTopic($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}