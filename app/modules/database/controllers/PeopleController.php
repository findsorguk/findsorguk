<?php
/** Controller for displaying information about people
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_PeopleController extends Pas_Controller_Action_Admin {

    protected $_peoples, $_geocoder;
    /** Setup the contexts by action and the ACL.
    */
    public function init() {
    $this->_helper->_acl->allow('flos',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addContext('csv',array('suffix' => 'csv'))
            ->addContext('vcf',array('suffix' => 'vcf'))
            ->addContext('rss',array('suffix' => 'rss'))
            ->addContext('atom',array('suffix' => 'atom'))
            ->addActionContext('person', array('xml','json','vcf'))
            ->addActionContext('index', array('xml','json'))
            ->initContext();

    $this->_peoples = new Peoples();
    $this->_geocoder = new Pas_Service_Geo_Coder();
	}

    const REDIRECT = 'database/people/';
    /** Index page of all people on the database
    */
    public function indexAction(){
    $form = new SolrForm();
    $form->removeElement('thumbnail');
    $form->q->setLabel('Search people: ');
    $form->q->setAttrib('placeholder','Try Bland for example');
    $this->view->form = $form;

    $params = $this->array_cleanup($this->_getAllParams());
    $search = new Pas_Solr_Handler();
    $search->setCore('beopeople');
    $search->setFields(array('*'));
    $search->setFacets(array('county','organisation','activity'));
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
            && !is_null($this->_getParam('submit'))){
    $params = $this->array_cleanup($form->getValues());
    $this->_helper->Redirector->gotoSimple('index','people','database',$params);
    } else {
    $params = $this->_getAllParams();
    $params['sort'] = 'surname';
    $params['direction'] = 'asc';
    $form->populate($this->_getAllParams());
    }
    if(!isset($params['q']) || $params['q'] == ''){
        $params['q'] = '*';
    }
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->createPagination();
    $this->view->results = $search->processResults();
    $this->view->facets = $search->processFacets();
    }


    private function array_cleanup( $array ) {
    $todelete = array('submit','action','controller','module','csrf');
    foreach( $array as $key => $value ) {
    foreach($todelete as $match){
    if($key == $match){
            unset($array[$key]);
    }
    }
    }
    return $array;
    }

    /** Display details of a person
    */
    public function personAction(){
    if($this->_getParam('id',false)) {
    $params = array();
    $person = $this->_peoples->getPersonDetails($this->_getParam('id'));
    if($this->_helper->contextSwitch()->getCurrentContext() !== 'vcf'){
    $search = new Pas_Solr_Handler();
    $search->setCore('beowulf');
    $fields = new Pas_Solr_FieldGeneratorFinds($this->_helper->contextSwitch()->getCurrentContext());
    $search->setFields($fields->getFields());
    $params['finderID'] = $person['0']['secuid'];
    $params['page'] = $this->_getParam('page');
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->createPagination();
    $this->view->finds = $search->processResults();
    }
    $this->view->peoples = $person;
    } else {
        throw new Exception($this->_missingParameter);
    }
    }

    /** Add personal data
    */
    public function addAction() {
    $secuid = $this->secuid();
    $form = new PeopleForm();
    $form->submit->setLabel('Add a new person');
    $form->removeElement('dbaseID');
    $form->removeElement('canRecord');
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
    $address = $form->getValue('address') . ',' . $form->getValue('city') . ','
    . $form->getValue('county') . ',' . $form->getValue('postcode');

    $coords = $this->_geocoder->getCoordinates($address);
    if($coords){
        $lat = $coords['lat'];
        $lon = $coords['lon'];
    } else {
        $lat = NULL;
        $lon = NULL;
    }
    $updateData['lat'] = $lat;
    $updateData['lon'] = $lon;
    $insert = $this->_peoples->add($updateData);

	$this->_helper->solrUpdater->update('beopeople', $insert);
    $this->_redirect(self::REDIRECT . 'person/id/' . $insert);
    $this->_flashMessenger->addMessage('Record created!');
    } else {
    $form->populate($form->getValues());
    }
    }
    }
    /** Edit person's data
    */
    public function editAction() {
    if($this->_getParam('id', false)) {
    $form = new PeopleForm();
    $form->submit->setLabel('Update details');
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
    $address = $form->getValue('address') . ',' . $form->getValue('city') . ','
    . $form->getValue('county') . ',' . $form->getValue('postcode');

    $coords = $this->_geocoder->getCoordinates($address);

    if($coords){
        $lat = $coords['lat'];
        $lon = $coords['lon'];
    } else {
        $lat = NULL;
        $lon = NULL;
    }

    $updateData['lat'] = $lat;
    $updateData['lon'] = $lon;

    $oldData = $this->_peoples->fetchRow('id=' . $this->_getParam('id'))->toArray();

    if(array_key_exists('dbaseID',$updateData)){
    $users = new Users();
    $userdetails = array('peopleID' => $oldData['secuid']);
    $userdetails['canRecord'] = $updateData['canRecord'];
    $whereUsers =  $users->getAdapter()->quoteInto('id = ?', $updateData['dbaseID']);
    $updateUsers = $users->update($userdetails, $whereUsers);
    }
    $where =  $this->_peoples->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    //Updated the people db table
    $updateData= $this->_peoples->updateAndProcess($updateData, $where);
    //Update the solr instance
    $update = $this->_peoples->update($updateData, $where);
    $this->_helper->solrUpdater->update('beopeople', $this->_getParam('id'));
    //Update the audit log
    $this->_helper->audit($updateData, $oldData, 'PeopleAudit',
            $this->_getParam('id'), $this->_getParam('id'));
    $this->_flashMessenger->addMessage('Person information updated!');
    $this->_redirect(self::REDIRECT . 'person/id/' . $this->_getParam('id'));
    } else {
    $form->populate($form->getValues());
    }
    } else {
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
    $form->populate($this->_peoples->fetchRow('id=' . $id)->toArray());
    }
    }
    } else {
    throw new Exception($this->_missingParameter);
    }
    }
    /** Delete a person's data
    */
    public function deleteAction() {
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = 'id = ' . $id;
    $this->_peoples->delete($where);
	$this->_helper->solrUpdater->deleteById('beopeople', $id);
    $this->_flashMessenger->addMessage('Record deleted!');
    }
    $this->_redirect(self::REDIRECT);
    }  else  {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->people = $this->_peoples->fetchRow('id=' . $id);
    }
    }
    }

    public function mapAction(){

    }
}
