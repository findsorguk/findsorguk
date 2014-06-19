<?php
/** 
 * Controller for CRUD of rallies recorded by the Scheme. Note not attended!
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller
 * @subpackage ActionAdmin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license    GNU General Public License
 * 
*/
class Database_RalliesController extends Pas_Controller_Action_Admin {

    protected $_cache;
    
    protected $_config;
    
    protected $_rallies;
    
    protected $_parishes;
        
    protected $_districts;
        
    /** Initialise the ACL and contexts
    */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->_acl->allow('public',array('index','rally','map'));
        $this->_helper->_acl->deny('public',array('addflo','delete','deleteflo'));
        $this->_helper->_acl->allow('flos',null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addContext('rss',array('suffix' => 'rss'))
                ->addContext('atom',array('suffix' => 'atom'))
                ->addActionContext('rally', array('xml','json'))
                ->addActionContext('index', array('xml','json','rss','atom'))
                ->initContext();
        $this->_cache = Zend_Registry::get('rulercache');
        $this->_rallies = new Rallies();
        $this->_districts = new OsDistricts();
        $this->_parishes = new OsParishes();
    }
    
    /** Set up the url for redirect
    */
    const URL = '/database/rallies/';
    
    /** Index page for the list of rallies.
    */
    public function indexAction() {
    $this->view->rallies  = $this->_rallies->getRallyNames((array)$this->_getAllParams());
    }
    /** Individual rally details
    */
    public function rallyAction() {
    if($this->_getParam('id',false)){
    $rallies = $this->_rallies->getRally($this->_getParam('id'));
    if(count($rallies)) {
    $this->view->rallies = $rallies;
    $attending = new RallyXFlo();
    $this->view->atts = $attending->getStaff($this->_getParam('id'));
    } else {
    throw new Exception('No rally exists with that id');
    }
    } else {
    throw new Exception($this->parameterMissing);
    }
    }

    /** Add a new rally
     * @todo move functionality to model
    */
    public function addAction() {
    $form = new RallyForm();
    $form->submit->setLabel('Add a new rally');
    $this->view->form = $form;
    if ($this->_request->isPost()) {
    $formData = $this->_request->getPost();
    if ($form->isValid($formData)) {
    $insert = $this->_rallies->addAndProcess($formData);
    $this->_cache->remove('rallydds');
    $this->_redirect(self::URL . 'rally/id/' . $insert);
    $this->_flashMessenger->addMessage('Details for ' . $form->getValue('rally_name')
    . ' have been created!');
    } else  {
    $data = $this->_request->getPost();
    $form->populate($data);
    if(array_key_exists('countyID', $data)){
    $district_list = $this->_districts->getDistrictsToCountyList($data['countyID']);
    $form->districtID->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
    }
    if(array_key_exists('districtID', $data)){
    $parish_list = $this->_parishes->getParishesToDistrictList($data['districtID']);
    $form->parishID->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
    }
    }
    }
    }
    /** Edit individual rally details
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
    $district_list = $this->_districts->getDistrictsToCountyList($formData['countyID']);
    $form->districtID->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
    $parish_list = $this->_parishes->getParishesToDistrictList($formData['districtID']);
    $form->parishID->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
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
    throw new Exception($this->_nothingFound);
    }

    $district_list = $this->_districts->getDistrictsToCountyList($rally['countyID']);
    $form->districtID->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
    $parish_list = $this->_parishes->getParishesToDistrictList($rally['districtID']);
    $form->parishID->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
    if(!is_null($rally['organiser'])) {
    $organisers = new Peoples();
    $organisers = $organisers->getName($rally['organiser']);
    foreach($organisers as $organiser) {
    $form->organisername->setValue($organiser['term']);
    }
    }
    }
    }
    } else {
            throw new Exception($this->_missingParameter);
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
    $this->_rallies->delete($where);
    $this->_cache->remove('rallydd');
    $this->_flashMessenger->addMessage('Record for rally deleted!');
    }
    $this->_redirect(self::URL);
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->rally = $this->_rallies->fetchRow('id=' . $id);
    }
    }
    }
    /** Add a flo to a rally as attending
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
    $insert = $rallies->insert($insertData);
    $this->_redirect(self::URL . 'rally/id/' . $rallyID);
    $this->_flashMessenger->addMessage('Finds Liaison Officer added to a rally');
    } else {
    $form->populate($formData);
    }
    }
    } else {
    throw new Pas_Exception_Param($this->_missingParameter);
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
    $this->_redirect(self::URL.'rally/id/'.$rallyID);
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
    */
    public function mapAction() {
    }

}