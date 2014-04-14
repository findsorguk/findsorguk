<?php
/** The find spots controller for CRUD to database
 * 
 *  This class allows for the creation, editing, updating and deletion of findspot 
 *  data. It makes use of a couple of webservices.
 * 
 * @author Daniel Pett
 * @category Pas
 * @package  Pas_Controller_Action_Admin
 * @subpackage Admin
 * @version 1
 * @license GNU 
 * @since September 2009
 * @todo move audit to own class
 * @todo DRY the class
 */
class Database_FindspotsController
	extends Pas_Controller_Action_Admin {

		
    protected $_findspots;
    

    /** Base Url redirect
     * 
     */
    const REDIRECT = '/database/artefacts/';

    /** Set up the ACL access and appid from config
     * 
     */
    public function init() {
    $this->_helper->_acl->deny('public',null);
    $this->_helper->_acl->allow('member',array('index','add','delete','edit'));
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_findspots = new Findspots();
    }

    /** The index page with no root access
    * 
    */
    public function indexAction() {
    $this->_flashMessenger->addMessage('You cannot access the findspots index.');
    $this->_redirect(self::REDIRECT);
    }

    /** Add a new findspot action
     * @todo The audit function needs abstracting to make thin controller happen.
     */
    public function addAction() {
    $finds = $this->_findspots->getFindtoFindspotsAdmin($this->_getParam('id'), 
            $this->_getParam('secuid'));
    if(sizeof($finds) > 0){
    throw new Exception('A findspot already exists for this record.', 500);
    }
    if($this->_getParam('id',false)){
    $form = new FindSpotForm();
    $returnID = $this->_getParam('id');
    $form->submit->setLabel('Add a findspot');
	$this->view->form = $form;
    if($this->_getParam('copy') === 'last') {
	$this->_helper->findspotFormOptions();
    }
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    $updateData = $form->getValues();
    $updateData['findID'] = $this->_getParam('secuid');
    $updateData['institution'] = $this->_helper->identity->getPerson()->institution;
    $this->_findspots->addAndProcess($updateData);
    $this->_helper->solrUpdater->update('beowulf', $returnID);
    $this->_redirect(self::REDIRECT . 'record/id/' . $returnID);
    $this->_flashMessenger->addMessage('A new findspot has been created.');
    } else {
    $form->populate($form->getValues());
    }
    } else {
    throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Action for editing findspots
     * 
     */
    public function editAction() {
    if($this->_getParam('id',false)) {
    $form = new FindSpotForm();
    $form->submit->setLabel('Update findspot');
    $this->view->form = $form;
    if($this->getRequest()->isPost() 
            && $form->isValid($this->_request->getPost())){
    $updateData = $form->getValues();
    $oldData = $this->_findspots->fetchRow('id=' 
            . $this->_getParam('id'))->toArray();
    $where = array();
    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', 
            $this->_getParam('id'));
    $insertData = $this->_findspots->updateAndProcess($updateData);
    $update = $this->_findspots->update($insertData, $where);
    $returnID = (int)$this->_findspots->getFindNumber($this->_getParam('id'));
    $this->_helper->audit($insertData, $oldData, 'FindSpotsAudit',
    $this->_getParam('id'), $returnID);
    $this->_helper->solrUpdater->update('beowulf', $returnID);
    $this->_flashMessenger->addMessage('Findspot updated!');
    $this->_redirect(self::REDIRECT . 'record/id/' . $returnID);
  
    } else {
    $id = (int)$this->_getParam('id', 0);
    if ($id > 0) {
    $where = array();
    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $findspot = $this->_findspots->fetchRow($where);
        $this->view->findspot = $findspot;
	$fill = new Pas_Form_Findspot();
	$fill->populate($findspot->toArray());
    }
    }
    } else {
        throw new Exception($this->_missingParameter,500);
    }
    }

    /** Action for deleting findspot
     * 
     */
    public function deleteAction() {
    if($this->_getParam('id',false)){
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $findID = (int)$this->_request->getPost('findID');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = 'id = ' . $id;
    $this->_findspots->delete($where);
	$this->_helper->solrUpdater->update('beowulf', $findID);
    $this->_flashMessenger->addMessage('Findspot deleted.');
    }
    $this->_redirect(self::REDIRECT . 'record/id/' . $findID);
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->findspot = $this->_findspots->getFindtoFindspotDelete($this->_getParam('id'));
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

}
