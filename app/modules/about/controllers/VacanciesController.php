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
class About_VacanciesController extends Pas_Controller_Action_Admin {
	
    /** The vacancies model
     * @access protected
     * @var \Vacancies
     */
    protected $_vacancies;
	
    /** Initialise the ACL and contexts
     * @access public
     * @return void
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
     * @access public
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
                'image' => $this->view->serverUrl() . '/assets/logos/pas.jpg',
                'email' => 'info@finds.org.uk',
                'copyright' => 'Creative Commons Licenced',
                'generator' => 'The Scheme database powered by Zend Framework and Dan\'s magic',
                'language' => 'en',
                'entries' => array()
		);
            foreach ($vacs as $vac) {
                $feedArray['entries'][] = array(
                    'title' => $vac['title'] . ' - ' . $vac['staffregions'],
                    'link' => $this->view->serverUrl() . '/about/vacancies/vacancy/id/' . $vac['id'],
                    'guid' => $this->view->serverUrl() . '/about/vacancies/vacancy/id/' . $vac['id'],
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
     * @access public
     */
    public function archivesAction(){
    $this->view->archives = $this->_vacancies->getArchiveJobs($this->_getParam('page'));
    }

    /** Render a vacancy's details
     * @access public
     * @throws Pas_Exception_Param if missing parameter on URL.
     */
    public function vacancyAction() {
        if($this->_getParam('id',false)){
            $this->view->vacs = $this->_vacancies->getJobDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}