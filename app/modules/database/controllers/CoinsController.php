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
    public function init()
    {
        $this->_helper->_acl->allow('member', array(
            'add', 'edit', 'delete',
            'coinref', 'editcoinref', 'deletecoinref'
        ));
        $this->_helper->_acl->allow('flos', null);
    }

    /** The base redirect
     *
     */
    const REDIRECT = '/database/artefacts/';

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
        if (($this->_getParam('broadperiod', false))
            && ($this->_getParam('findID', false))
        ) {
            if($this->getCoins()->checkCoinData($this->_getParam('findID'))) {
                throw new Pas_Exception('Record already exists', 500);
            }
            $broadperiod = (string)$this->_getParam('broadperiod');
            $form = $this->_helper->coinFormLoader($broadperiod);
            $this->view->form = $form;
            $last = $this->_getParam('copy');
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
                $insertData['findID'] = (string)$this->_getParam('findID');
                $insertData['secuid'] = (string)$this->secuid();
                $insertData['institution'] = $this->getInstitution();
                $this->getCoins()->add($insertData);
                $this->_helper->solrUpdater->update('objects', $this->_getParam('returnID'));
                $this->getFlash()->addMessage('Coin data saved.');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
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
    public function editAction()
    {
        if ($this->_getParam('id', false)) {
            $finds = new Finds();
            $this->view->finds = $finds->getFindNumbersEtc($this->_getParam('returnID'));
            $form = $this->_helper->coinFormLoader($this->_getParam('broadperiod'));
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();
                    $oldData = $this->getCoins()->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    $where = $this->getCoins()->getAdapter()->quoteInto('id = ?',
                        $this->_getParam('id'));
                    //Update the coins table
                    $this->getCoins()->update($updateData, $where);
                    //Audit the changes
                    $this->_helper->audit($updateData, $oldData, 'CoinsAudit', $this->_getParam('id'), $this->_getParam('returnID'));
                    //Update solr index
                    $this->_helper->solrUpdater->update('objects', $this->_getParam('returnID'));
                    $this->getFlash()->addMessage('Numismatic details updated.');
                    $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                $id = (int)$this->_getParam('id', 0);
                if ($id > 0) {
                    $coin = $this->getCoins()->getCoinToEdit($id);
                    $form->populate($coin['0']);
                    $this->_helper->coinFormLoaderOptions($this->_getParam('broadperiod'), $coin);
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
        if ($this->_getParam('id', false)) {
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $returnID = (int)$this->_request->getPost('returnID');
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = 'id = ' . $id;
                    $this->getCoins()->delete($where);
                    $this->getFlash()->addMessage('Numismatic data deleted!');
                    $this->redirect(self::REDIRECT . 'record/id/' . $returnID);
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
        if (!isset($params['findID'])) {
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
                $this->getFlash()->addMessage('Coin reference data saved.');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
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
                    'findID' => (string)$this->_getParam('findID'),
                    'classID' => $form->getValue('classID'),
                    'vol_no' => $form->getValue('vol_no'),
                    'reference' => $form->getValue('reference')
                );

                $where = array();
                $where[] = $coins->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                $coins->update($updateData, $where);
                $this->getFlash()->addMessage('Coin reference updated!');
                $this->redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
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
        $returnID = $this->_getParam('returnID');
        $this->view->returnID = $returnID;
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $coins = new CoinXClass();
                $where = $coins->getAdapter()->quoteInto('id = ?', $id);
                $this->_helper->solrUpdater->update('objects', $returnID);
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