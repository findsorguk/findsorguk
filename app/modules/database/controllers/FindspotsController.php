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
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since September 2009
 * @uses Findspots
 * @uses Pas_Exception_Param
 * @uses Exception
 * @uses FindSpotForm
 * @uses Pas_Form_Findspot
 */
class Database_FindspotsController extends Pas_Controller_Action_Admin
{

    /** The findspots model
     * @access protected
     * @var \Findspots
     */
    protected $_findspots;

    /** The controller to redirect to on completion of action
     * @access protected
     * @var \Findspots
     */
    protected $_controller;

    /** The redirect URL to go to on completion of action
     * @access protected
     * @var \Findspots
     */
    protected $_redirect;

    /** Set the controller to redirect to on completion of action
     * @access public
     * @param string $recordtype
     * @return \Findspots
     */
    public function setController($recordtype)
    {
        $this->_controller = $recordtype;
        return $this;
    }

    /** Set the redirect URL to go to on completion of action
     * @access public
     * @return \Findspots
     */
    public function setRedirect($controller)
    {
        $module = '/database/';
        $this->_redirect = $module . $controller . '/';
        return $this;
    }

    /** Get the controller to redirect to on completion of action
     * @access public
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /** Get the redirect URL to go to on completion of action
     * @access public
     * @return string
     */
    public function getRedirect()
    {
        return $this->_redirect;
    }


    /** Set up the ACL access and appid from config
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->deny('public', null);
        $this->_helper->_acl->allow('member', array('index', 'add', 'delete', 'edit'));
        $this->_helper->_acl->allow('admin', array('updatehoards'));
        $this->setController($this->getParam('recordtype', 'artefacts'));
        $this->setRedirect($this->getController());
        $this->_findspots = new Findspots();
    }

    /** The index page with no root access
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the findspots index.');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect($this->getRedirect());
    }

    /** Add a new findspot action
     * @todo The audit function needs abstracting to make thin controller happen.
     * @return void
     * @access public
     * @throws Exception
     * @throws Pas_Exception_Param
     */
    public function addAction()
    {
        $finds = $this->_findspots->getFindtoFindspotsAdmin(
            $this->getParam('id'),
            $this->getParam('secuid')
        );
        if (sizeof($finds) > 0) {
            throw new Exception('A findspot already exists for this record.', 500);
        }
        //Check for parameter
        if ($this->getParam('id', false)) {

            $form = new FindSpotForm();
            $returnID = $this->getParam('id');
            $form->submit->setLabel('Add a findspot');

            $this->view->form = $form;

            if ($this->getParam('copy') === 'last') {
                $this->_helper->findspotFormOptions();
            }
            // Check if post
            if ($this->getRequest()->isPost()) {
                // Check if valid
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();
                    $updateData['findID'] = $this->getParam('secuid');
                    $updateData['institution'] = $this->_helper->identity->getPerson()->institution;
                    $this->_findspots->addAndProcess($updateData);
                    $this->_helper->solrUpdater->update('objects', $returnID);
                    $this->redirect($this->getRedirect() . 'record/id/' . $returnID);
                    $this->getFlash()->addMessage('A new findspot has been created.');
                } else {
                    $form->populate($this->_request->getPost());
                    $this->_helper->findspotFailedOptions($this->_request->getPost());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Action for editing findspots
     * @access public
     * @return void
     * @throws Exception
     */
    public function editAction()
    {
        if ($this->getParam('id', false)) {
            $form = new FindSpotForm();
            $form->submit->setLabel('Update find spot');
            $this->view->form = $form;
            $returnID = (int)$this->_findspots->getFindNumber($this->getParam('id'), $this->getParam('recordtype'));
            $this->view->returnID = $returnID;
            //Check if POST
            if ($this->getRequest()->isPost()) {
                // Check if valid
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();
                    $oldData = $this->_findspots->fetchRow('id=' . $this->getParam('id'))->toArray();
                    $where = array();
                    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?',
                        $this->getParam('id'));
                    $insertData = $this->_findspots->updateAndProcess($updateData);
                    $this->_findspots->update($insertData, $where);
                    $returnID = (int)$this->_findspots->getFindNumber($this->getParam('id'), $this->getController());
                    $this->_helper->audit($insertData, $oldData, 'FindspotsAudit', $this->getParam('id'), $returnID);
                    $this->_helper->solrUpdater->update('objects', $returnID, $this->getParam('recordtype'));
                    $this->getFlash()->addMessage('Findspot updated!');
                    $this->redirect($this->getRedirect() . 'record/id/' . $returnID);
                } else {
                    // If error fill with posted values
                    $form->populate($this->_request->getPost());
//                    Zend_Debug::dump($this->_helper->findspotFailedOptions($this->_request->getPost()));
                }
            } else {
                // As GET, refill from db
                $where = array();
                $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $findSpot = $this->_findspots->fetchRow($where);
                if (!is_null($findSpot)) {
                    $this->view->findspot = $findSpot;
                    $fill = new Pas_Form_Findspot();
                    $fill->populate($findSpot->toArray());
                } else {
                    throw new Pas_Exception('No row found in database', 500);
                }

            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Action for deleting findspot
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function deleteAction()
    {
        if ($this->getParam('id', false)) {
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $recordID = (int)$this->_request->getPost('recordID');
                $this->setController($this->_request->getPost('controller'));
                $this->setRedirect($this->getController());
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = 'id = ' . $id;
                    $this->_findspots->delete($where);
                    $this->_helper->solrUpdater->update('objects', $findID);
                    $this->getFlash()->addMessage('Findspot deleted.');
                }
                $this->redirect($this->getRedirect() . 'record/id/' . $recordID);
            } else {
                $id = (int)$this->_request->getParam('id');
                if ($id > 0) {
                    $this->view->findspot = $this->_findspots
                        ->getFindtoFindspotDelete($this->getParam('id'), $this->getController());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    public function updatehoardsAction()
    {
        $findspots = $this->_findspots;
        $records = $findspots->getNewData('IARCH');
        foreach($records as $data) {
            echo 'Updating ' . $data['id'] . '<br/>';
            $newData = $this->_findspots->updateAndProcessGrids($data);
            $where = array();
            $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', $newData['id']);
            $this->_findspots->update($newData, $where);
            Zend_Debug::dump($data);
            usleep(2000);
        }
        echo 'Done';
    }
}