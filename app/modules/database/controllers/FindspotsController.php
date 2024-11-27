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

    private const FINDSPOTAUDITMODEL = 'FindspotsAudit';

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
        $this->_helper->_acl->allow('member', array('index', 'add', 'delete', 'edit', 'error'));
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

    /**
     * Get the find institution for a given find record ID
     *
     * This function is used as part of findspot edit permissions.
     *
     * @param int $findID The ID of the find record
     * @return string The institution of the find record
     * @throws Pas_Exception If the find record has no institution set
     */
    private function getFindInstitution(int $findID){
        $finds = new Finds();
        $findInstitution = $finds->getInstitutionForRecord($findID);

        if (empty($findInstitution)) {
            throw new Pas_Exception("Record $findID has no institution set", 404);
        }

        if (!isset($findInstitution[0]['institution'])) {
            throw new Pas_Exception("Record $findID has no institution set", 404);
        }

        return $findInstitution[0]['institution'];
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

                    //Set findspot institution to the same as the record
                    $updateData['institution'] = $this->getFindInstitution($returnID);

                    $this->_findspots->addAndProcess($updateData);
                    $this->_helper->solrUpdater->update('objects', $returnID, $this->getParam('recordtype'));

                    // Add to audit table
                    $originalRecordData = [];
                    $this->_helper->audit(
                        $updateData,
                        $originalRecordData,
                        self::FINDSPOTAUDITMODEL,
                        $returnID,
                        $returnID
                    );

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

            // Set the findspot institution to that of the record for permission checks.
            // This resolves pre 1.82 behaviour where findspot institution can differ from the records
            $findInstitution = $this->getFindInstitution($returnID);
            $this->view->recordInstitution = $findInstitution;

            $this->view->returnID = $returnID;
            //Check if POST
            if ($this->getRequest()->isPost()) {
                // Check if valid
                if ($form->isValid($this->_request->getPost())) {
                    $updateData = $form->getValues();

                    // Update findspot to be the same as record. Fix for pre 1.82 behaviour.
                    $updateData['institution'] = $findInstitution;

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

                    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $findSpot = $this->_findspots->fetchRow($where);
                    $this->view->findspot = $findSpot;
                    $form->populate($this->_request->getPost());
                    $this->_helper->findspotFailedOptions($this->_request->getPost());
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
            $type = $this->getParam('recordtype');
            $recordID = $this->getParam('recordID');
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $this->view->recordID = (int)$this->_request->getPost('recordID');
                $this->view->type = $type;
                $this->setController($this->_request->getPost('type'));
                $this->setRedirect($this->getController());
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = 'id = ' . $id;
                    $this->_findspots->delete($where);
                    $this->_helper->solrUpdater->update('objects', $recordID, $this->_request->getPost('type'));
                    $this->getFlash()->addMessage('Findspot deleted.');
                }
                $this->redirect($this->getRedirect() . 'record/id/' . $recordID);
            } else {
                $id = (int)$this->_request->getParam('id');
                $type = $this->getParam('recordtype');
                if ($id > 0) {
                    $this->view->findspot = $this->_findspots->getFindtoFindspotDelete($this->getParam('id'), $this->getController());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 404);
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
