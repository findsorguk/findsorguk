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
        $this->getResponse()->setHttpResponseCode(301)
            ->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect(self::REDIRECT);
    }

    /** Action for adding coin summary
     * @access public
     */
    public function addAction()
    {
        if ($this->_getParam('id', false) || $this->getParam('secUID', false)) {
            $form = $this->getForm();
            $this->view->form = $form;
            if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['hoardID'] = $this->_getParam('secUID');
                $this->getModel()->add($data);
                $this->getFlash()->addMessage('You have added a summary record');
                $this->redirect('/database/hoards/record/id/' . $this->_getParam('id'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit action for coin summary
     * @access public
     */
    public function editAction()
    {
        if ($this->_getParam('id', false) || $this->_getParam('hoardID', false)) {
            $form = $this->getForm();
            $this->view->form = $form;
            // Check if POST
            if ($this->getRequest()->isPost()) {
                // Check if form is valid
                if ($form->isValid($this->_request->getPost())) {
                    //Where array
                    $where = array();
                    $where[] = $this->getModel()->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    //Set up auditing
                    $oldData = $this->getModel()->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    //Get the data and update based on where value
                    $this->getModel()->update($form->getValues(), $where);
                    $this->_helper->audit(
                        $updateData,
                        $oldData,
                        'SummaryAudit',
                        $this->_getParam('id'),
                        $this->_getParam('id')
                    );
                    $this->getFlash()->addMessage('You have edited data successfully');
                    $this->redirect('/database/hoards/record/id/' );
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                $form->populate($this->getModel()->fetchRow('id=' . $this->_getParam('id'))->toArray());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete action for coin summary
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
                //$this->_helper->solrUpdater->deleteById('beowulf', $id);
                $this->redirect('database/hoards/record/id/' . $id);
            } elseif ($del == 'No' && $id > 0) {
                $this->getFlash()->addMessage('No changes made!');
                $this->redirect('database/hoards/record/id/' . $id);
            }
        } else {
            $this->view->summary = $this->getModel()->fetchRow('id=' . $this->_request->getParam('id'));
        }
    }
}