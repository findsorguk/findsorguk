<?php
/** Controller for getting vacancy data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_VacanciesController extends Pas_Controller_Action_Admin {
	
	protected $_vacancies;
	
	/** Initialise the ACL and contexts
	*/
    public function init() {
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->acl->allow('public',null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
    	->addContext('rss',array('suffix' => 'rss'))
    	->addContext('atom',array('suffix' => 'atom'))
    	->addActionContext('index', array('xml','json','rss','atom'))
    	->addActionContext('archives', array('xml','json'))
    	->addActionContext('vacancy', array('xml','json'))
    	->initContext();
    $this->_vacancies = new Vacancies();
    }

	/** Render the index pages and rss
	*/
	public function indexAction() {
		if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('rss','atom'))) {
		$this->_helper->layout->disableLayout();
		$vacs = $this->_vacancies->getJobs(25);
		$feedArray = array(
			'title' => 'Current vacancies at the Portable Antiquities Scheme',
			'link' => $this->view->CurUrl(),
			'charset' => 'utf-8',
			'description' => 'The latest vacancies at the Portable Antiquities Scheme',
			'author' => 'The Portable Antiquities Scheme',
			'image' => Zend_Registry::get('siteurl').'/images/logos/pas.gif',
			'email' => 'info@finds.org.uk',
			'copyright' => 'Creative Commons Licenced',
			'generator' => 'The Scheme database powered by Zend Framework and Dan\'s magic',
			'language' => 'en',
			'entries' => array()
		);
		foreach ($vacs as $vac) {
			$feedArray['entries'][] = array(
				'title' => $vac['title'] . ' - ' . $vac['staffregions'],
				'link' => Zend_Registry::get('siteurl').'/getinvolved/vacancies/vacancy/id/'.$vac['id'],
				'guid' => Zend_Registry::get('siteurl').'/getinvolved/vacancies/vacancy/id/'.$vac['id'],
				'description' => strip_tags(substr($vac['specification'],0,300)),
				'lastUpdate' => strtotime($vac['created']),
				'content' => strip_tags($vac['specification'],''),
				);
		}
  		 $feed = Zend_Feed::importArray($feedArray, $this->_getParam('format'));
		 $feed->send();
		} else {
		$this->view->vacs = $this->_vacancies->getLiveJobs($this->_getParam('page'));
		$this->view->delicious = $this->_helper->Config()->webservice->delicious;
		}
	}

	/** Render the archives section
	*/
	public function archivesAction(){
	$this->view->archives = $this->_vacancies->getArchiveJobs($this->_getParam('page'));
	}

	/** Render a vacancy's details
	* @throws Pas_Exception_Param if missing parameter on URL.
	*/
	public function vacancyAction() {
	if($this->_getParam('id',false)){
		$this->view->vacs = $this->_vacancies->getJobDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}