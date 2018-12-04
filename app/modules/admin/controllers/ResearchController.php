<?php

/** Controller for adding and manipulating research and topics
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses ResearchProjects
 * @uses SuggestedResearch
 * @uses ResearchForm
 *
 */
class Admin_ResearchController extends Pas_Controller_Action_Admin
{

    /** The research project model
     * @access protected
     * @var \ResearchProjects
     */
    protected $_research;

    /** Suggested research model
     * @access protected
     * @var \SuggestedResearch
     */
    protected $_suggested;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_research = new ResearchProjects();
        $this->_suggested = new SuggestedResearch();
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);

    }

    /** Set up the redirect baseurl
     * @var string REDIRECT
     */
    const REDIRECT = '/admin/research/';

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->research = $this->_research->getAllProjects($this->getAllParams());
    }

    /** Add a new research topic
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new ResearchForm();
        $form->submit->setLabel('Add a project');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->_research->add($form->getValues());
                $this->getFlash()->addMessage('A new research project has been entered.');
                $this->redirect(self::REDIRECT);
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a research project
     * @access public
     * @return void
     * @uses ResearchForm
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
        if ($this->getParam('id', false)) {
            $form = new ResearchForm();
            $form->submit->setLabel('Submit changes to project');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $where = $this->_research->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $this->_research->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Research project details updated.');
                    $this->redirect(self::REDIRECT);
                } else {
                    $form->populate($form->getValues());
                }
            } else {

                $form->populate($this->_research->fetchRow('id=' . $this->_request->getParam('id'))->toArray());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

	/** Delete a research topic
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = $this->_research->getAdapter()->quoteInto('id = ?', $id);
                $this->_research->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->redirect(self::REDIRECT);
            }
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->research = $this->_research->fetchRow('id=' . $id);
            }
        }
    }

    /** Add a suggested research topic
     * @access public
     * @return void
     */
    public function addsuggestedAction()
    {
        $form = new SuggestedForm();
        $form->submit->setLabel('Add a project');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->_suggested->add($form->getValues());
                $this->getFlash()->addMessage('A new suggested research project has been entered.');
                $this->redirect(self::REDIRECT . 'suggested/');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** List all suggested topics
     * @access public
     * @return void
     */
    public function suggestedAction()
    {
        $this->view->suggested = $this->_suggested->getAll($this->getAllParams(), 0);
    }

    /** Edit a suggested topic
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editsuggestedAction()
    {
        if ($this->getParam('id', false)) {
            $form = new SuggestedForm();
            $form->submit->setLabel('Submit changes to project');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $where = $this->_suggested->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $this->_suggested->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Suggested research project details updated.');
                    $this->redirect(self::REDIRECT . 'suggested/');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $form->populate($this->_suggested->fetchRow('id=' . $id)->toArray());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a suggested topic
     * @access public
     * @return void
     */
    public function deletesuggestedAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = $this->_suggested->getAdapter()->quoteInto('id = ?', $id);
                $this->_suggested->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->redirect(self::REDIRECT . 'suggested/');
            }
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->suggest = $this->_suggested->fetchRow('id=' . $id);
            }
        }
    }
}
