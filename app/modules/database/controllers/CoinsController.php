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
class Database_CoinsController extends Pas_Controller_Action_Admin
{

    /** The base redirect
     *
     */
    const REDIRECT = '/database/artefacts/';
    /** The coins model
     * @access protected
     * @var \Coins
     */
    protected $_coins;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('member', array(
            'add', 'edit', 'delete',
            'coinref', 'editcoinref', 'deletecoinref'
        ));
        $this->_helper->_acl->allow('flos', null);
    }

    /** Redirect as no direct access to the coins index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the root page for coins');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('database/search/results/');
    }

    /** Add a coin's data
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function addAction()
    {
        if (($this->getParam('broadperiod', false))
            && ($this->getParam('findID', false))
        ) {
            if ($this->getCoins()->checkCoinData($this->getParam('findID'))) {
                throw new Pas_Exception('Record already exists', 500);
            }
            $broadperiod = (string)$this->getParam('broadperiod');
            $form = $this->_helper->coinFormLoader($broadperiod);
            $this->view->form = $form;
            $last = $this->getParam('copy');
            if ($last == 'last') {
                $this->getFlash()->addMessage('Cloned your last record.');
                $coindata = $this->getCoins()->getLastRecord($this->getIdentityForForms());
                foreach ($coindata as $coindataflat) {
                    $form->populate($coindataflat);
                    $this->_helper->coinFormLoaderOptions($broadperiod, $coindataflat);
                }
            }
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                $insertData = $form->getValues();
                $insertData['findID'] = (string)$this->getParam('findID');
                $insertData['secuid'] = (string)$this->secuid();
                $insertData['institution'] = $this->getInstitution();
                $this->getCoins()->add($insertData);
                $this->_helper->solrUpdater->update('objects', $this->getParam('returnID'), 'artefacts');
                $this->getFlash()->addMessage('Coin data saved.');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->getParam('returnID'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Get the coins model
     * @access public
     * @return \Coins
     */
    public function getCoins()
    {
        $this->_coins = new Coins();
        return $this->_coins;
    }

    /** Edit coin data
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
        if ($this->getParam('id', false)) {
            $finds = new Finds();
            $this->view->finds = $finds->getFindNumbersEtc($this->getParam('returnID'));
            $form = $this->_helper->coinFormLoader($this->getParam('broadperiod'));
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();
                    $oldData = $this->getCoins()->fetchRow('id=' . $this->getParam('id'))->toArray();
                    $where = $this->getCoins()->getAdapter()->quoteInto('id = ?',
                        $this->getParam('id'));
                    //Update the coins table
                    $this->getCoins()->update($updateData, $where);
                    //Audit the changes
                    $this->_helper->audit($updateData, $oldData, 'CoinsAudit', $this->getParam('id'), $this->getParam('returnID'));
                    //Update solr index
                    $this->_helper->solrUpdater->update('objects', $this->getParam('returnID'), 'artefacts');
                    $this->getFlash()->addMessage('Numismatic details updated.');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->getParam('returnID'));
                } else {
                    $form->populate($this->_request->getPost());
                    $this->view->coin = $this->getCoins()->getCoinToEdit($id);
                }
            } else {
                $id = (int)$this->getParam('id', 0);
                if ($id > 0) {
                    $coin = $this->getCoins()->getCoinToEdit($id);
                    $this->view->coin = $coin;
                    $form->populate($coin['0']);
                    $this->_helper->coinFormLoaderOptions($this->getParam('broadperiod'), $coin);
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
    public function deleteAction()
    {

        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $recordID = (int)$this->_request->getPost('returnID');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = array();
                $where[] = $this->getCoins()->getAdapter()->quoteInto('id = ?', $id);
                $this->getCoins()->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->_helper->solrUpdater->update('objects', $recordID, 'artefacts');
                $this->redirect(self::REDIRECT . 'record/id/' . $recordID);
            } elseif ($del == 'No' && $id > 0) {
                $this->getFlash()->addMessage('No changes made!');
                $this->redirect(self::REDIRECT . 'record/id/' . $recordID);
            }
        } else {
            $this->view->coins = $this->getCoins()->getFindToCoinDelete($this->getParam('id'));
        }
    }

    /** Link coin reference to object
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function coinrefAction()
    {
        $params = $this->getAllParams();
        if (!isset($params['returnID']) && !isset($params['findID'])) {
            throw new Pas_Exception_Param('Find ID and return ID missing');
        }
        if (!isset($params['returnID'])) {
            throw new Pas_Exception_Param('The return ID parameter is missing.');
        }
        $form = new ReferenceCoinForm();
        $form->submit->setLabel('Add reference');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $coins = new CoinXClass();
                $insertData = array(
                    'findID' => $this->getParam('returnID'),
                    'classID' => $form->getValue('classID'),
                    'vol_no' => $form->getValue('vol_no'),
                    'reference' => $form->getValue('reference')
                );
                $coins->add($insertData);
                $this->getFlash()->addMessage('Coin reference data saved.');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->getParam('returnID'));
            } else {
                $form->populate($formData);
            }
        }
    }

    /** Edit a coin reference to object
     * @access public
     * @return void
     */
    public function editcoinrefAction()
    {
        $form = new ReferenceCoinForm();
        $form->submit->setLabel('Edit reference');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $coins = new CoinXClass();
                $updateData = array(
                    'classID' => $form->getValue('classID'),
                    'vol_no' => $form->getValue('vol_no'),
                    'reference' => $form->getValue('reference')
                );

                $where = array();
                $where[] = $coins->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $coins->update($updateData, $where);
                $this->getFlash()->addMessage('Coin reference updated!');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->getParam('returnID'));
            } else {
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
    public function deletecoinrefAction()
    {
        $returnID = $this->getParam('returnID');
        $this->view->returnID = $returnID;
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $coins = new CoinXClass();
                $where = $coins->getAdapter()->quoteInto('id = ?', $id);
                $this->getFlash()->addMessage('Record deleted!');
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