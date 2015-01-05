<?php
/** Controller for managing jettons etc
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Coins
 * @uses Pas_Exception
 * @uses Pas_Exception_Param
 * @uses TokenJettonForm
 * @uses Finds
 *
 */
class Database_JettonsController extends Pas_Controller_Action_Admin {

    /** The coins model
     * @access protected
     * @var \Coins
     */
    protected $_coins;

    public function getCoins()
    {
        $this->_coins = new Coins();
        return $this->_coins;
    }

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->_acl->allow('member',array('add','edit','delete'));
        $this->_helper->_acl->allow('flos',null);
    }

    /** The redirect script
     *
     */
    const REDIRECT = '/database/artefacts/';

    /** Redirect of the user due to no action existing.
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->getFlash()->addMessage('There is not a root action for jettons');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect(self::REDIRECT);
    }

    /** Add jetton data
     * @todo rewrite for audit etc
     * @throws Pas_Exception
     * @throws Pas_Exception_Param
     * @access public
     * @return void
     */
    public function addAction() {
        if( ($this->getParam('broadperiod',false))
            && ($this->getParam('findID',false) )){
            $this->getCoins()->checkCoinData($this->getParam('findID'));
            $broadperiod = (string)$this->getParam('broadperiod');
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
                    throw new Pas_Exception('You cannot have a token for that period.');
            }

            $last = $this->getParam('copy');
            if($last == 'last') {
                $this->getFlash()->addMessage('Your last record data has been cloned');
                $coindata = $this->getCoins()->getLastRecord($this->getIdentityForForms());
                foreach($coindata as $coindataflat){
                    $form->populate($coindataflat);
                }
            }
            $this->view->form = $form;
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $insertData = $form->getValues();
                    $insertData['secuid'] = $this->secuid();
                    $insertData['findID'] = $this->getParam('findID');
                    $this->getCoins()->add($insertData);
                    $this->_helper->solrUpdater->update('objects', $this->getParam('returnID'));
                    $this->getFlash()->addMessage('Jetton data saved for this record.');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->getParam('returnID'));
                }  else {
                    $form->populate($this->_request->getPost());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit jetton data
     * @todo rewrite for audit etc
     * @access public
     * @return void
     */
    public function editAction() {
        if($this->getParam('id',false)){
            $finds = new Finds();
            $this->view->finds = $finds->getFindNumbersEtc($this->getParam('returnID'));
            $broadperiod = (string)$this->getParam('broadperiod');
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
                    throw new Pas_Exception('You cannot have a jetton for that period.');
            }
            $this->view->form = $form;
            if($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();
                    $oldData = $this->getCoins()->fetchRow('id=' . $this->getParam('id'))->toArray();
                    $where =  $this->getCoins()->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $this->getCoins()->update($updateData, $where);
                    $this->_helper->audit($updateData, $oldData, 'CoinsAudit', $this->getParam('id'), $this->getParam('returnID'));
                    $this->getFlash()->addMessage('Numismatic details updated.');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->getParam('returnID'));
                    $this->_helper->solrUpdater->update('objects', $this->getParam('returnID'));
                } else {
                    $this->getFlash()->addMessage('Please check your form for errors');
                    $form->populate($this->_request->getPost());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->getParam('id', 0);
                if (is_int($id)) {
                    $coin = $this->_coins->fetchRow('id=' . $this->getParam('id'))->toArray();
                    $form->populate($coin);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete jetton data
     * @access public
     * @return void
     */
    public function deleteAction() {
        if($this->getParam('id',false)){
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $returnID = (int)$this->_request->getPost('returnID');
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = 'id = ' . $id;
                    $this->getCoins()->delete($where);
                    $this->getFlash()->addMessage('Numismatic data deleted!');
                    $this->_helper->solrUpdater->update('objects', $returnID);
                    $this->redirect(self::REDIRECT.'record/id/' . $returnID);
                }
            } else {
                $id = (int)$this->_request->getParam('id');
                if ($id > 0) {
                    $this->view->coins = $this->getCoins()->getFindToCoinDelete($id);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}