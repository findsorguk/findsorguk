<?php

/** Controller for manipulating sketchfab models
 *
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
class Database_SketchFabController extends Pas_Controller_Action_Admin
{

    /** The sketchfab model
     * @access protected
     * @var \SketchFab
     */
    protected $_sketchFab;

    /** The sketchfab form
     * @access protected
     * @var \SketchFabForm
     */
    protected $_sketchFabForm;

    /** Base Url redirect
     *
     */
    const REDIRECT = '/database/artefacts/record/';

    /** Get the archaeology form
     * @access public
     * @return \SketchFabForm
     */
    public function getSketchFabForm()
    {
        $this->_sketchFabForm = new SketchFabForm();
        return $this->_sketchFabForm;
    }

    /** The archaeology model */
    protected $_model;

    /** Get the archaeology model
     * @return mixed \SketchFab
     */
    public function getModel()
    {
        $this->_model = new SketchFab();
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

    /** Add a new SketchFab model
     * @return void
     * @access public
     * @throws Exception
     * @throws Pas_Exception_Param
     */
    public function addAction()
    {
        if ($this->_getParam('findID', false)) {
            $form = $this->getSketchFabForm();
            $form->submit->setLabel('Add a model');
            $this->view->form = $form;
            // Check if request is POST and whether valid
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                // Get data
                $data = $form->getValues();
                $data['findID'] = $this->_getParam('findID');
                // Add the data
                $this->getModel()->add($data);
                //Add a flash message
                $this->getFlash()->addMessage('You have added a model to the record');
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

    /** Edit the sketchfab model details
     * @return void
     * @access public
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
        //Check if parameter for ID exists
        if ($this->_getParam('findID', false)) {
            $form = $this->getSketchFabForm();
            // Check if the id parameter exists
            $form->submit->setLabel('Edit model data');
            $this->view->form = $form;
            // Check if POST
            if ($this->getRequest()->isPost()) {
                // Check if form valid
                if ($form->isValid($this->_request->getPost())) {
                    // Create where clause array
                    $where = array();
                    $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    // Get the data and update based on where value
                    $this->getModel()->update($form->getValues(), $where);
                    // Add flash message and redirect back to record
                    $this->getFlash()->addMessage('You have edited the model details');
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
            $findID = $this->_request->getPost('findID');
            if ($del == 'Yes' && $id > 0) {
                $where = array();
                $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $id);
                $where[] = $this->getModel()->getAdapter()->quoteInto('findID = ?', $findID);
                $this->getModel()->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->_helper->solrUpdater->update('hoards', $findID);
                $this->redirect('database/hoards/record/id/' . $findID);
            } elseif ($del == 'No' && $id > 0) {
                $this->getFlash()->addMessage('No changes made!');
                $this->redirect('database/hoards/record/id/' . $findID);
            }
        } else {
            $this->view->hoard = $this->getModel()->fetchRow('id=' . $this->_request->getParam('id'));
        }
    }

}