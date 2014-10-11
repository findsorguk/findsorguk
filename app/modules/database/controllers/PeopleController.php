<?php
/** Controller for displaying information about people
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses People
 * @uses Pas_Service_Geo_Coder
 * @uses SolrForm
 * @uses Pas_Solr_Handler
 * @uses PeopleForm
 * @uses Pas_Exception_Parameter
 * @uses Users
 * @uses Pas_ArrayFunctions
*/
class Database_PeopleController extends Pas_Controller_Action_Admin {

    /** The people model
     * @access protected
     * @var \People
     */
    protected $_people;

    /** The geocoder
     * @access protected
     * @var \Pas_Service_Geo_Coder
     */
    protected $_geocoder;

    /** The current context
     * @access protected
     * @var string
     */
    protected $_currentContext;

    /** Get the current content used
     * @access public
     * @return string
     */
    public function getCurrentContext() {
        $this->_currentContext = $this->_helper->contextSwitch()
                ->getCurrentContext();
        return $this->_currentContext;
    }

    /** Get people from the model
     * @access public
     * @return \People
     */
    public function getPeople() {
        $this->_people = new People();
        return $this->_people;
    }

    /** Get the geocoder
     * @access public
     * @return \Pas_Service_Geo_Coder
     */
    public function getGeocoder() {
        $this->_geocoder = new Pas_Service_Geo_Coder();
        return $this->_geocoder;
    }

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->allow('flos',null);
        
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addContext('csv',array('suffix' => 'csv'))
                ->addContext('vcf',array('suffix' => 'vcf'))
                ->addContext('rss',array('suffix' => 'rss'))
                ->addContext('atom',array('suffix' => 'atom'))
                ->addActionContext('person', array('xml','json','vcf'))
                ->addActionContext('index', array('xml','json'))
                ->initContext();
    }

    /** Redirect base string
     *
     */
    const REDIRECT = 'database/people/';

    /** Index page of all people on the database
     * @access public
     * @return void
     */
    public function indexAction(){
        $form = new SolrForm();
        $form->removeElement('thumbnail');
        $form->q->setLabel('Search people: ');
        $form->q->setAttrib('placeholder','Try Bland for example');
        $this->view->form = $form;
        $search = new Pas_Solr_Handler();
        $search->setCore('beopeople');
        $search->setFields(array('*'));
        $search->setFacets(array('county','organisation','activity'));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){
            $cleaner = new Pas_ArrayFunctions();
            $params = $cleaner->array_cleanup($form->getValues());
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

    /** Display details of a person
     * @access public
     * @return void
     */
    public function personAction(){
        if($this->_getParam('id',false)) {
            $params = array();
            $person = $this->_peoples->getPersonDetails($this->_getParam('id'));
            if($this->_helper->contextSwitch()->getCurrentContext() !== 'vcf'){
                $search = new Pas_Solr_Handler();
                $search->setCore('beowulf');
                $fields = new Pas_Solr_FieldGeneratorFinds($this->getCurrentContext());
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
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }


    /** Add personal data
     * @access public
     * @return void
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
                $address = $form->getValue('address');
                $address .= ',';
                $address .= $form->getValue('city');
                $address .= ',';
                $address .= $form->getValue('county');
                $address .= ',';
                $address .= $form->getValue('postcode');
                $coords = $this->geoCodeAddress($address);
                $insertData = array_merge($updateData, $coords);
                $insert = $this->getPeople()->add($insertData);
        	$this->_helper->solrUpdater->update('beopeople', $insert);
                $this->redirect(self::REDIRECT . 'person/id/' . $insert);
                $this->getFlash()->addMessage('Record created!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit person's data
     * @access public
     * @throws Exception
     */
    public function editAction() {
        if($this->_getParam('id', false)) {
            $form = new PeopleForm();
            $form->submit->setLabel('Update details');
            $this->view->form = $form;
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $updateData = $form->getValues();
                    $address = $form->getValue('address');
                    $address .= ',';
                    $address .= $form->getValue('city');
                    $address .= ',';
                    $address .= $form->getValue('county');
                    $address .= ',';
                    $address .= $form->getValue('postcode');
                    $coords = $this->geoCodeAddress($address);
                    $oldData = $this->getPeople()->fetchRow('id='
                            . $this->_getParam('id'))->toArray();
                    if(array_key_exists('dbaseID',$updateData)){
                        $users = new Users();
                        $userdetails = array('peopleID' => $oldData['secuid']);
                        $userdetails['canRecord'] = $updateData['canRecord'];
                        $whereUsers =  $users->getAdapter()->quoteInto('id = ?', $updateData['dbaseID']);
                        $users->update($userdetails, $whereUsers);
                    }
                    $where =  $this->getPeople()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $merged = array_merge($updateData, $coords);
                    //Updated the people db table
                    $clean= $this->getPeople()->updateAndProcess($merged);
                    //Update the solr instance
                    $this->getPeople()->update($clean, $where);
                    $this->_helper->solrUpdater->update('beopeople', $this->_getParam('id'));
                    //Update the audit log
                    $this->_helper->audit($updateData, $oldData, 'PeopleAudit',
                    $this->_getParam('id'), $this->_getParam('id'));
                    $this->getFlash()->addMessage('Person information updated!');
                    $$this->redirect(elf::REDIRECT . 'person/id/' . $this->_getParam('id'));
                    } else {
                        $form->populate($form->getValues());
                    }
                } else {
                    $id = (int)$this->_request->getParam('id', 0);
                    if ($id > 0) {
                        $form->populate($this->getPeople()->fetchRow('id=' . $id)->toArray());
                    }
            }
            } else {
                throw new Exception($this->_missingParameter);
            }
    }

    /** Delete a person's data
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->getPeople()->delete($where);
                $this->_helper->solrUpdater->deleteById('beopeople', $id);
                $this->getFlash()->addMessage('Record deleted!');
            }
            $this->_redirect(self::REDIRECT);
            }  else  {
                $id = (int)$this->_request->getParam('id');
                if ($id > 0) {
                    $this->view->people = $this->getPeople()->fetchRow('id=' . $id);
                }
        }
    }

    /** An action to map the people
     * @access public
     * @todo make this more useful
     * @return void
     */
    public function mapAction(){
        //All magic happens in the view
    }

    /** Geocode the coordinates of the address
     * @access public
     * @param string $address
     * @return array
     */
    public function geoCodeAddress( $address ) {
        $coords = $this->getCoder()->getCoordinates($address);
        $latlon = array();
        if($coords){
            $latlon['lat'] = $coords['lat'];
            $latlon['lon'] = $coords['lon'];
        }
        return $latlon;
    }
}