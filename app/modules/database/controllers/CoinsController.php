<?php
/** Controller for displaying information about coins
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Coins
 * @uses Zend_Controller_Request_Http
 * @uses Pas_Exception_Param
 * @uses Finds
 * @uses CoinXClass
 * @uses ReferenceCoinForm
*/
class Database_CoinsController extends Pas_Controller_Action_Admin {

    /** The coins model
     * @access protected
     * @var \Coins
     */
    protected $_coins;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->allow('member',array(
            'add', 'edit', 'delete',
            'coinref', 'editcoinref', 'deletecoinref'
            ));
        $this->_helper->_acl->allow('flos',null);
        $this->_coins = new Coins();
    }

    /** The base redirect
     *
     */
    const REDIRECT = '/database/artefacts/';

    /** Redirect as no direct access to the coins index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->_helper->flashMessenger->addMessage('No access to root page.');
        $this->redirect(Zend_Controller_Request_Http::getServer('referer'));
    }
    /** Add a coin's data
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function addAction() {
        if( ($this->_getParam('broadperiod',false))
                && ($this->_getParam('findID',false) )){
            $exist = $this->_coins->checkCoinData($this->_getParam('findID'));
            $broadperiod = (string)$this->_getParam('broadperiod');
            $form = $this->_helper->coinFormLoader($broadperiod);
            $this->view->form = $form;
            $last = $this->_getParam('copy');
            if($last == 'last') {
                $this->_helper->flashMessenger->addMessage('Cloned your last record.');
                $coindata = $this->_coins->getLastRecord($this->getIdentityForForms());
                foreach($coindata as $coindataflat){
                    $form->populate($coindataflat);
                    $this->_helper->coinFormLoaderOptions($broadperiod,
                            $coindataflat);
                }
            }
            if($this->getRequest()->isPost()
                    && $form->isValid($this->_request->getPost())){
                $insertData  = $form->getValues();
                $insertData['findID'] = (string) $this->_getParam('findID');
                $insertData['secuid'] = (string) $this->secuid();
                $insertData['institution'] = $this->getInstitution();
                $this->_coins->add($insertData);
                $this->_helper->solrUpdater->update('objects',
                        $this->_getParam('returnID'));
                $this->_helper->flashMessenger->addMessage('Coin data saved.');
                $this->redirect(self::REDIRECT . 'record/id/'
                        . $this->_getParam('returnID'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit coin data
     * @access public
     * @return void
     * @throws Pas_Exception_Param
    */
    public function editAction() {
        if($this->_getParam('id',false)){
            $finds = new Finds();
            $this->view->finds = $finds->getFindNumbersEtc($this->_getParam('returnID'));
            $broadperiod = (string)$this->_getParam('broadperiod');
            $form = $this->_helper->coinFormLoader($broadperiod);
            $this->view->form = $form;
            if($this->getRequest()->isPost()
                    && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $updateData = $form->getValues();
                    $oldData = $this->_coins->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    $where =  $this->_coins->getAdapter()->quoteInto('id = ?',
                            $this->_getParam('id'));
                        //Update the coins table
                    $update = $this->_coins->update($updateData, $where);
                    //Audit the changes
                    $this->_helper->audit($updateData, $oldData, 'CoinsAudit',
                            $this->_getParam('id'), $this->_getParam('returnID'));
                    //Update solr index
                    $this->_helper->solrUpdater->update('objects', $this->_getParam('returnID'));
                    $this->_helper->flashMessenger->addMessage('Numismatic details updated.');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                $id = (int)$this->_getParam('id', 0);
                if ($id > 0) {
                    $coin = $this->_coins->getCoinToEdit($id);
                    $form->populate($coin['0']);
                    $formLoader = $this->_helper->coinFormLoaderOptions($broadperiod,  $coin);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete coin data via primary key
     * @access public
     * @return void
    */
    public function deleteAction() {
        if($this->_getParam('id',false)){
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $returnID = (int)$this->_request->getPost('returnID');
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = 'id = ' . $id;
                    $this->_coins->delete($where);
                    $this->_helper->flashMessenger->addMessage('Numismatic data deleted!');
                    $this->redirect(self::REDIRECT . 'record/id/' . $returnID);
                }
            } else {
                $id = (int)$this->_request->getParam('id');
                if ($id > 0) {
                    $this->view->coins = $this->_coins->getFindToCoinDelete($id);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Link coin reference to object
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function coinrefAction() {
        $params = $this->_getAllParams();
        if(!isset($params['returnID']) && !isset($params['findID'])) {
            throw new Pas_Exception_Param('Find ID and return ID missing');
        }
        if(!isset($params['returnID'])) {
            throw new Pas_Exception_Param('The return ID parameter is missing.');
        }
        if(!isset($params['findID'])) {
            throw new Pas_Exception_Param('The find ID parameter is missing.');
        }
        $form = new ReferenceCoinForm();
        $form->submit->setLabel('Add reference');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $coins = new CoinXClass();
                $secuid = $this->secuid();
                $insertData = array(
                    'findID' => (string)$this->_getParam('findID'),
                    'classID' => $form->getValue('classID'),
                    'vol_no' => $form->getValue('vol_no'),
                    'reference' => $form->getValue('reference')
                );
                $coins->insert($insertData);
                $this->_helper->flashMessenger->addMessage('Coin reference data saved.');
                $this->redirect(self::REDIRECT.'record/id/' . $this->_getParam('returnID'));
            } else {
                $form->populate($formData);
            }
        }
    }

    /** Edit a coin reference to object
     * @access public
     * @return void
    */
    public function editcoinrefAction()	{
        $form = new ReferenceCoinForm();
        $form->submit->setLabel('Edit reference');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $coins = new CoinXClass();
                $updateData = array(
                    'findID' => (string)$this->_getParam('findID'),
                    'classID' => $form->getValue('classID'),
                    'vol_no' => $form->getValue('vol_no'),
                    'reference' => $form->getValue('reference')
                );

                $where = array();
                $where[] = $coins->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                $coins->update($updateData,$where);
                $this->_helper->flashMessenger->addMessage('Coin reference updated!');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
            }  else {
                $form->populate($this->_request->getPost());
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $coins = new CoinXClass();
                $coins = $coins->fetchRow('id=' . $id);
                $form->populate($coins->toArray());
            }
        }
    }
    /** Delete a coin reference to object
     * @access public
     * @return void
     */
    public function deletecoinrefAction() {
        $returnID = $this->_getParam('returnID');
        $this->view->returnID = $returnID;
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $coins = new CoinXClass();
                $where = $coins->getAdapter()->quoteInto('id = ?', $id);
                $this->_helper->solrUpdater->update('objects', $returnID);
                $this->_helper->flashMessenger->addMessage('Record deleted!');
                $coins->delete($where);
                $this->redirect(self::REDIRECT . 'record/id/' . $returnID);
            }
            $this->redirect('database/artefacts/record/id/' . $returnID);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $coins = new CoinXClass();
                $this->view->coin = $coins->fetchRow('id=' . $id);
            }
        }
    }
}