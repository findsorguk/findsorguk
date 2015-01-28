<?php

/** The coin summary controller.
 * This is used for adding coin summaries for the hoard record.
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2014 Mary Chester-Kadwell
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class Database_SummaryController extends Pas_Controller_Action_Admin
{

    /** The redirect for index */
    const REDIRECT = '/';

    /** The form
     * @access protected
     */
    protected $_form;

    /** Get the Summary model
     * @access protected
     * @var
     */
    protected $_model;

    /** Get the form
     * @return \CoinSummaryForm
     */
    public function getForm()
    {
        $this->_form = new CoinSummaryForm();
        return $this->_form;
    }

    /** Get the model
     * @access public
     * @return \CoinSummary
     */
    public function getModel()
    {
        $model = new CoinSummary();
        return $model;
    }

    /** Init all the permissions in ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->deny('public', null);
        $this->_helper->_acl->allow('member', array('index'));
        $this->_helper->_acl->allow('member', array('add', 'delete', 'edit'));
    }

    /** Index action for coin summary
     * @return void
     * @access public
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the summary index.');
        $this->getResponse()->setHttpResponseCode(301)->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect(self::REDIRECT);
    }

    /** Action for adding coin summary
     * @access public
     */
    public function addAction()
    {
        if ($this->getParam('id', false) || $this->getParam('secUID', false)) {
            $form = $this->getForm();
            $this->view->form = $form;
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['hoardID'] = $this->getParam('secUID');
                $this->getModel()->add($data);
                $this->_helper->solrUpdater->update('coinsummary', $this->getParam('id'));
                $this->getFlash()->addMessage('You have added a summary record');
                $this->redirect('/database/hoards/record/id/' . $this->getParam('id'));
            } else {
                // Populate the form with the posted values
                $form->populate($this->_request->getPost());
                // Configure the dropdowns with correct menu values
                $this->_helper->coinSummaryFormLoaderOptions($this->_request->getPost());
            }
        } else {
            //Parameters missing so through exception
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit action for coin summary
     * @access public
     */
    public function editAction()
    {
        if ($this->getParam('id', false) || $this->getParam('hoardID', false)) {
            $form = $this->getForm();
            $this->view->form = $form;
            // Check if POST
            if ($this->getRequest()->isPost()) {
                // Check if form is valid
                if ($form->isValid($this->_request->getPost())) {
                    // Where array
                    $where = array();
                    $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    // Set up auditing
                    $oldData = $this->getModel()->fetchRow('id=' . $this->getParam('id'))->toArray();
                    // Get the data and update based on where value
                    $this->getModel()->update($form->getValues(), $where);
                    // Audit the data being entered
                    $this->_helper->audit( $form->getValues(), $oldData, 'SummaryAudit', $this->getParam('hoardID'), $this->getParam('hoardID'));
                    $this->_helper->solrUpdater->update('coinsummary', $this->getParam('id'));
                    // Add flash message
                    $this->getFlash()->addMessage('You have edited data successfully');
                    // Redirect back to record
                    $this->redirect('/database/hoards/record/id/' . $this->getParam('hoardID') );
                } else {
                    // Error thrown, populate form with values
                    $form->populate($this->_request->getPost());
                    // Configure with correct values based on choices
                    $this->_helper->coinSummaryFormLoaderOptions($this->_request->getPost());
                }
            } else {
                // As GET request, populate with data
                $form->populate($this->getModel()->fetchRow('id=' . $this->getParam('id'))->toArray());
                // Configure menus appropriately
                $this->_helper->coinSummaryFormLoaderOptions($this->getModel()->fetchRow('id=' . $this->getParam('id'))->toArray());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete action for coin summary
     */
    public function deleteAction()
    {
        $hoardID = $this->getParam('hoardID');
        $this->view->hoardID = $hoardID;
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = array();
                $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $id);
                $this->getModel()->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->_helper->solrUpdater->deleteById('coinsummary', $id);
                $this->redirect('database/hoards/record/id/' . $hoardID);
            } elseif ($del == 'No' && $id > 0) {
                $this->getFlash()->addMessage('No changes made!');
                $this->redirect('database/hoards/record/id/' . $hoardID);
            }
        } else {
            $this->view->summary = $this->getModel()->fetchRow('id=' . $this->_request->getParam('id'));
        }
    }
}