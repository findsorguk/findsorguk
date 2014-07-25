<?php
/** 
 * Controller for CRUD of rallies recorded by the Scheme. Note not attended!
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses RallyXFlo
 * @uses Rallies
 * @uses OsParishes
 * @uses OsDistricts
 * @uses RallyForm
 * @uses AddFloRallyForm
 * @uses People
*/
class Database_RalliesController extends Pas_Controller_Action_Admin {

    /** The rallies model
     * @access protected
     * @var \Rallies
     */
    protected $_rallies;
    
    /** The parishes model
     * @access protected
     * @var \OsParishes
     */
    protected $_parishes;
        
    /** The districts model
     * @access protected
     * @var \OsDistricts
     */
    protected $_districts;
    
    /** The people model
     * @access protected
     * @var \People
     */
    protected $_people;
    
    /** Get the people model
     * @access public
     * @return \People
     */
    public function getPeople() {
        return $this->_people;
    }
        
    /** The rallies model retrieved
     * @access public
     * @return \Rallies
     */
    public function getRallies() {
        $this->_rallies = new Rallies();
        return $this->_rallies;
    }

    /** The parishes model returned
     * @access public
     * @return \OsParishes
     */
    public function getParishes() {
        $this->_parishes = new OsParishes();
        return $this->_parishes;
    }

    /** The districts model returned
     * @access public
     * @return \OsDistricts
     */
    public function getDistricts() {
        $this->_districts = new OsDistricts();
        return $this->_districts;
    }
            
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->_acl->allow('public',array('index','rally','map'));
        $this->_helper->_acl->deny('public',array('addflo','delete','deleteflo'));
        $this->_helper->_acl->allow('flos',null);
        $this->_helper->contextSwitch()
                ->setAutoJsonSerialization(false)
                ->setAutoDisableLayout(true)
                ->addContext('rss',array('suffix' => 'rss'))
                ->addContext('atom',array('suffix' => 'atom'))
                ->addActionContext('rally', array('xml','json'))
                ->addActionContext('index', array('xml','json','rss','atom'))
                ->initContext();
    }
    
    /** Set up the url for redirect
    */
    const URL = '/database/rallies/';
    
    /** Index page for the list of rallies.
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->rallies  = $this->getRallies()->getRallyNames((array)$this->_getAllParams());
    }
    /** Individual rally details
     * @access public
     * @return void
     * @throws Pas_Param_Exception
     */
    public function rallyAction() {
        if($this->_getParam('id',false)){
        $rallies = $this->getRallies()->getRally($this->_getParam('id'));
        if(count($rallies)) {
            $this->view->rallies = $rallies;
            $attending = new RallyXFlo();
            $this->view->atts = $attending->getStaff($this->_getParam('id'));
        } else {
            throw new Pas_Param_Exception('No rally exists with that id', 404);
        }
        } else {
            throw new Pas_Param_Exception($this->parameterMissing, 404);
        }
    }

    /** Add a new rally
     * @access public
     * @return void
     * @todo move functionality to model
    */
    public function addAction() {
        $form = new RallyForm();
        $form->submit->setLabel('Add a new rally');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
        $formData = $this->_request->getPost();
        if ($form->isValid($formData)) {
            $insert = $this->getRallies()->addAndProcess($formData);
            $this->_cache->remove('rallydds');
            $this->_redirect(self::URL . 'rally/id/' . $insert);
            $this->_flashMessenger->addMessage('Details for ' 
                    . $form->getValue('rally_name')
                    . ' have been created!');
        } else  {
            $data = $this->_request->getPost();
            $form->populate($data);
            if(array_key_exists('countyID', $data)){
                $district_list = $this->_districts->getDistrictsToCountyList($data['countyID']);
                $form->districtID->addMultiOptions(array(
                    null => 'Choose a district',
                    'Available districts' => $district_list
                        ));
            }
            if(array_key_exists('districtID', $data)){
                $parish_list = $this->_parishes->getParishesToDistrictList($data['districtID']);
                $form->parishID->addMultiOptions(array(
                    null => 'Choose a parish',
                    'Available parishes' => $parish_list
                        ));
            }
        }
        }
    }
    /** Edit individual rally details
     * @access public
     * @return void
     * @todo DRY this
     * @throws Pas_Param_Exception
     */
    public function editAction() {
        if($this->_getParam('id',false)){
        $form = new RallyForm();
        $form->submit->setLabel('Update details');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $updateData = $this->_rallies->updateAndProcess($formData);
                $where = array();
                $where[] = $this->_rallies->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                unset($updateData['created']);
                $update = $this->_rallies->update($updateData, $where);
                $this->_cache->remove('rallydds');
                $this->_flashMessenger->addMessage('Rally information updated!');
                $this->_redirect(self::URL . 'rally/id/' . $this->_getParam('id'));
            } else {
                if(!is_null($formData['districtID'])) {
                    $district_list = $this->_districts
                            ->getDistrictsToCountyList($formData['countyID']);
                    $form->districtID->addMultiOptions(array(
                    null => 'Choose a district',
                    'Available districts' => $district_list
                        ));
                    $parish_list = $this->_parishes
                            ->getParishesToDistrictList($formData['districtID']);
                    $form->parishID->addMultiOptions(array(
                    null => 'Choose a parish',
                    'Available parishes' => $parish_list
                        ));
                }
                $form->populate($formData);
            }
        } else {
            // find id is expected in $params['id']
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $rally = $this->_rallies->fetchRow('id='.$id);
                if($rally) {
                    $form->populate($rally->toArray());
                } else {
                    throw new Pas_Param_Exception($this->_nothingFound, 404);
                }

                $district_list = $this->_districts
                        ->getDistrictsToCountyList($rally['countyID']);
                $form->districtID->addMultiOptions(array(
                    null => 'Choose a district',
                    'Available districts' => $district_list
                        ));
                $parish_list = $this->_parishes
                        ->getParishesToDistrictList($rally['districtID']);
                $form->parishID->addMultiOptions(array(
                    null => 'Choose a parish',
                    'Available parishes' => $parish_list
                        ));
                if(!is_null($rally['organiser'])) {
                    $organisers = $this->getPeople()->getName($rally['organiser']);
                    foreach($organisers as $organiser) {
                        $form->organisername->setValue($organiser['term']);
                    }
                }
            }
        }
        } else {
            throw new Pas_Param_Exception($this->_missingParameter, 404);
        }
    }
    /** Delete rally details
    */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->getRallies()->delete($where);
                $this->_cache->remove('rallydd');
                $this->_flashMessenger->addMessage('Record for rally deleted!');
            }
            $this->_redirect(self::URL);
        } else {
        $id = (int)$this->_request->getParam('id');
        if ($id > 0) {
        $this->view->rally = $this->getRallies()->fetchRow('id=' . $id);
        }
        }
    }
    /** Add a flo to a rally as attending
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function addfloAction() {
        if($this->_getParam('id',false)) {
        $form = new AddFloRallyForm();
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $rallies = new RallyXFlo();
                $rallyID = $this->_getParam('id');
                $insertData = array(
                'rallyID' => $rallyID,
                'staffID' => $form->getValue('staffID'),
                'dateFrom' => $form->getValue('dateFrom'),
                'dateTo' => $form->getValue('dateTo'),
                'created' => $this->getTimeForForms(),
                'createdBy' => $this->getIdentityForForms()
                        );
                $rallies->insert($insertData);
                $this->_redirect(self::URL . 'rally/id/' . $rallyID);
                $this->_flashMessenger->addMessage('Finds Liaison Officer added to a rally');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete an attending flo
    */
    public function deletefloAction() {
        if ($this->_request->isPost()) {
            $staffID = (int)$this->_request->getPost('staffID');
            $rallyID = (int)$this->_request->getPost('rallyID');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes') {
                $rallies = new RallyXFlo();
                $where = array();
                $where[] = $this->_rallies->getAdapter()->quoteInto('staffID = ?', (int)$staffID);
                $where[] = $this->_rallies->getAdapter()->quoteInto('rallyID = ?', (int)$rallyID);
                $rallies->delete($where);
                $this->_flashMessenger->addMessage('Attending FLO for rally deleted!');
            }
            $this->_redirect(self::URL.'rally/id/' . $rallyID);
        } else {
            $rallyID = (int)$this->_request->getParam('rallyID');
            $staffID = (int)$this->_request->getParam('staffID');
            $rallies = new RallyXFlo();
            $where = array();
            $where[] = $rallies->getAdapter()->quoteInto('staffID = ?', (int)$staffID);
            $where[] = $rallies->getAdapter()->quoteInto('rallyID = ?', (int)$rallyID);
            $this->view->rally = $rallies->fetchRow($where);
        }
    }
    /** Display a map of attended rallies
     * @access public
     * @return void
     */
    public function mapAction() {
        //Magic in view
    }

}