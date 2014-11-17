<?php

/** Controller for manipulating acronyms on the system
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Acronyms
 * @uses AcronymForm
 *
 */
class Admin_AcronymsController extends Pas_Controller_Action_Admin
{

    /** The acronyms model
     * @access protected
     * @var \Acronyms
     */
    protected $_acronyms;

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
        $this->_acronyms = new Acronyms();

    }

    /** The redirect URI
     *
     */
    const REDIRECT = '/admin/acronyms/';

    /** Display all the acronyms
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->acronyms = $this->_acronyms->getAllAcronyms($this->getAllParams());
    }

    /** Add a new acronym
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new AcronymForm();
        $form->details->setLegend('Add an acronym: ');
        $form->submit->setLabel('Add new acronym');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()
            && $form->isValid($this->_request->getPost())
        ) {
            if ($form->isValid($form->getValues())) {
                $this->_acronyms->add($form->getValues());
                $this->getFlash()->addMessage('A new acronym has been created.');
                $this->redirect(self::REDIRECT);
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit an acronym
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
        if ($this->_getParam('id', false)) {
            $form = new AcronymForm();
            $form->details->setLegend('Edit an acronym: ');
            $form->submit->setLabel('Save new acronym details');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())
            ) {
                if ($form->isValid($form->getValues())) {
                    $updateData = $form->getValues();
                    $where = array();
                    $where[] = $this->_acronyms->getAdapter()->quoteInto('id = ?',
                        $this->_getParam('id'));
                    $update = $this->_acronyms->update($updateData, $where);
                    $this->getFlash()->addMessage('Acronym details updated.');
                    $this->redirect(self::REDIRECT);
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $acro = $this->_acronyms->fetchRow('id=' . $id)->toArray();
                    $this->view->acro = $acro;
                    $form->populate($acro);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete an acronym
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->_acronyms->delete($where);
            }
            $this->redirect(self::REDIRECT);
            $this->getFlash()->addMessage('Record deleted!');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->acro = $this->acronyms->fetchRow('id=' . $id);
            }
        }
    }
}