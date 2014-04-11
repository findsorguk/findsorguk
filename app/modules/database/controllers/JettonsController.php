<?php
/** Controller for managing jettons etc
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_JettonsController extends Pas_Controller_Action_Admin {
	
    protected $_coins;
    /** Setup the contexts by action and the ACL.
    */	
    public function init()  {	
    $this->_helper->_acl->allow('member',array('add','edit','delete'));
    $this->_helper->_acl->allow('flos',null);
    $this->_coins = new Coins();
    }
    const REDIRECT = '/database/artefacts/';
    /** redirect of the user due to no action existing.
    */
    public function indexAction() {
    $this->_helper->flashMessenger->addMessage('There is not a root action for jettons');
    $this->_redirect(Zend_Controller_Request_Http::getServer('referer'));
    }
    /** Add jetton data
     * @todo rewrite for audit etc
    */
    public function addAction() {
    if( ($this->_getParam('broadperiod',false)) && ($this->_getParam('findID',false) )){
    $exist = $this->_coins->checkCoinData($this->_getParam('findID'));
    $broadperiod = (string)$this->_getParam('broadperiod');

    switch ($broadperiod) {
        case 'MEDIEVAL':
            $form = new TokenJettonForm();
            $form->details->setLegend('Add Medieval jetton data');
            $form->submit->setLabel('Add jetton data');
            $this->view->headTitle('Add a Medieval jetton\'s details');
            break; 
        case 'POST MEDIEVAL':
            $form = new TokenJettonForm();
            $form->details->setLegend('Add Post Medieval jetton data');
            $form->submit->setLabel('Add jetton data');
            $this->view->headTitle('Add a Post Medieval jetton\'s details');
            break; 
        default:
            throw new Exception('You cannot have a token for that period.');
                    break;
    }		


    $last = $this->_getParam('copy');
    if($last == 'last') {
    $this->_helper->flashMessenger->addMessage('Your last record data has been cloned');
    $coindata = $this->_coins->getLastRecord($this->getIdentityForForms());
    foreach($coindata as $coindataflat){
    $form->populate($coindataflat);
    }
    }
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($_POST)){
    if ($form->isValid($form->getValues())) {
    $secuid = $this->secuid();
    $insertData = $form->getValues();
    $insertData['secuid'] = $this->secuid();
    $insertData['findID'] = $this->_getParam('findID');
    $insert = $this->_coins->add($insertData);
	$this->_helper->solrUpdater->update('beowulf', $this->_getParam('returnID'));
    $this->_helper->flashMessenger->addMessage('Jetton data saved for this record.');
    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
    }  else {
    $form->populate($_POST);
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Edit jetton data
     * @todo rewrite for audit etc
    */		
    public function editAction() {
    if($this->_getParam('id',false)){
    $finds = new Finds();
    $this->view->finds = $finds->getFindNumbersEtc($this->_getParam('returnID'));
    $broadperiod = (string)$this->_getParam('broadperiod');
    switch ($broadperiod) {
        case 'MEDIEVAL':
            $form = new TokenJettonForm();
            $form->details->setLegend('Edit Medieval jetton data');
            $form->submit->setLabel('Save data');
            $this->view->headTitle('Edit a Medieval jetton\'s details');
            break; 
        case 'POST MEDIEVAL':
            $form = new TokenJettonForm();
            $form->details->setLegend('Edit Post Medieval jetton data');
            $form->submit->setLabel('Save data');
            $this->view->headTitle('Edit a Post Medieval jetton\'s details');
            break; 
        default:
        throw new Exception('You cannot have a jetton for that period.');
            break;
    }		
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();

    $oldData = $this->_coins->fetchRow('id=' . $this->_getParam('id'))->toArray();

    $where =  $this->_coins->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));

    $update = $this->_coins->update($updateData, $where);

    $this->_helper->audit($updateData, $oldData, 'CoinsAudit', 
            $this->_getParam('id'), $this->_getParam('returnID'));

    $this->_helper->flashMessenger->addMessage('Numismatic details updated.');

    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
    
    $this->_helper->solrUpdater->update('beowulf', $this->_getParam('returnID'));

    } else {
    $this->_helper->flashMessenger->addMessage('Please check your form for errors');
    $form->populate($_POST);
    }
    } else {
    // find id is expected in $params['id']
    $id = (int)$this->_getParam('id', 0);
    if (is_int($id)) {
    $coin = $this->_coins->fetchRow('id=' . $this->_getParam('id'))->toArray();
    $form->populate($coin);
    }
    }
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
    /** Delete jetton data
    */	
    public function deleteAction() {
    if($this->_getParam('id',false)){
    $this->view->headTitle('Delete coin data');
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $returnID = (int)$this->_request->getPost('returnID');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = 'id = ' . $id;
    $this->_coins->delete($where);
    $this->_helper->flashMessenger->addMessage('Numismatic data deleted!');
    $this->_helper->solrUpdater->update('beowulf', $returnID);
    $this->_redirect(self::REDIRECT.'record/id/' . $returnID);
    }
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->coins = $this->_coins->getFindToCoinDelete($id);
    }
    }
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

}