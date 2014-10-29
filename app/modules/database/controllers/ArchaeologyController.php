<?php

/** Controller for manipulating the archaeological context data
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2014 Mary Chester-Kadwell
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Archaeology
 * @uses ArchaeologyForm
 */
class Database_ArchaeologyController extends Pas_Controller_Action_Admin
{

    /** The archaeological context model
     * @access protected
     * @var \Archaeology
     */
    protected $_archaeology;

    /** The archaeological context form
     * @access protected
     * @var \Archaeology
     */
    protected $_archaeologyForm;

    /** Base Url redirect
     *
     */
    const REDIRECT = '/database/hoards/record/';

    /** Get the archaeology form
     * @access public
     * @return \ArchaeologyForm
     */
    public function getArchaeologyForm()
    {
        $this->_archaeologyForm = new ArchaeologyForm();
        return $this->_archaeologyForm;
    }

    /** The archaeology model */
    protected $_model;

    /** Get the archaeology model
     * @return mixed \Archaeology
     */
    public function getModel()
    {
        $this->_model = new Archaeology();
        return $this->_model;
    }

    /** Set up the ACL access and appid from config
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->deny('public', null);
        $this->_helper->_acl->allow('member', array('index'));
        $this->_helper->_acl->allow('member', array('add', 'delete', 'edit'));
        $this->_archaeology = new Archaeology();
    }

    /** The index page with no root access
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the archaeological context index.');
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('/');
    }

    /** Add a new archaeological context
     * @return void
     * @access public
     * @throws Exception
     * @throws Pas_Exception_Param
     */
    public function addAction()
    {
        // Check if data already added, if so redirect back.
        if ($this->getModel()->fetchRow('id=' . $this->_getParam('id'))) {
            $this->getFlash()->addMessage('Archaeological context already exists on record');
            // Redirect back to the record
            $this->redirect(self::REDIRECT . 'id/' . $this->getParam('id'));
        }
        if ($this->_getParam('id', false) || $this->_getParam('hoardID', false)) {
            $form = $this->getArchaeologyForm();
            $form->submit->setLabel('Add archaeological context');
            $this->view->form = $form;
            // Check if request is POST and whether valid
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                // Get data
                $data = $form->getValues();
                $data['hoardID'] = $this->_getParam('hoardID');
                // Add the data
                $this->getModel()->add($data);
                //Add a flash message
                $this->getFlash()->addMessage('You have added archaeology to the record');
                // Redirect back to the record
                $this->redirect(self::REDIRECT . 'id/' . $this->getParam('id'));
            } else {
                // Form was not valid so fill with posted values
                $form->populate($this->_request->getPost());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit the archaeological context
     * @return void
     * @access public
     * @throws Pas_Exception_Param
     * @todo Add SOLR update logic
     */
    public function editAction()
    {
        //Check if parameter for ID exists
        if ($this->_getParam('id', false)) {
            $form = $this->getArchaeologyForm();
            // Check if the id parameter exists
            $form->submit->setLabel('Edit archaeological context');
            $this->view->form = $form;
            // Check if POST
            if ($this->getRequest()->isPost()) {
                // Check if form valid
                if ($form->isValid($this->_request->getPost())) {
                    // Create where clause array
                    $where = array();
                    $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    // Set up auditing by grabbing old data
                    $oldData = $this->getModel()->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    // Get the data and update based on where value
                    $this->getModel()->update($form->getValues(), $where);
                    // Perform comparison audit between old and new data
                    $this->_helper->audit(
                        $form->getValues(),
                        $oldData,
                        'ArchaeologyAudit',
                        $this->getParam('id'),
                        $this->getParam('id')
                    );
                    // Add SOLR update logic here when ready

                    $this->_helper->solrUpdater->update('hoards',  $this->getParam('id'));
                    // Add flash message and redirect back to record
                    $this->getFlash()->addMessage('You have edited some archaeology successfully');
                    // Now redirect to the correct URL
                    $this->redirect(self::REDIRECT . 'id/' . $this->getParam('id'));
                } else {
                    // Repopulate with the posted values
                    $form->populate($this->_request->getPost());
                }
            } else {
                // If GET, then populate with data from model
                $form->populate($this->getModel()->fetchRow('id=' . $this->_getParam('id'))->toArray());
            }
        } else {
            // As parameter missing, throw exception and set code
            throw new Pas_Exception($this->_missingParameter, 500);
        }
    }

    /** Delete the archaeological context
     * @return void
     * @access public
     *
     */
    public function deleteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            $hoardID = $this->_request->getPost('hoardID');
            if ($del == 'Yes' && $id > 0) {
                $where = array();
                $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $id);
                $where[] = $this->getModel()->getAdapter()->quoteInto('hoardID = ?', $hoardID);
                $this->getModel()->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->_helper->solrUpdater->update('hoards', $hoardID);
                $this->redirect('database/hoards/record/id/' . $hoardID);
            } elseif ($del == 'No' && $id > 0) {
                $this->getFlash()->addMessage('No changes made!');
                $this->redirect('database/hoards/record/id/' . $hoardID);
            }
        } else {
            $this->view->hoard = $this->getModel()->fetchRow('id=' . $this->_request->getParam('id'));
        }
    }

}