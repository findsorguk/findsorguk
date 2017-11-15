<?php

/** Controller for setting up and manipulating institutional copyrights
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Copyrights
 * @uses CopyrightForm
 * @uses Pas_Exception_Param
 *
 */
class Admin_CopyrightsController extends Pas_Controller_Action_Admin
{

    /** The copyrights model
     * @access protected
     * @var \Copyrights
     */
    protected $_copyrights;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('admin', null);
        $this->_copyrights = new Copyrights();

    }

    /** Set up the index of institutional copyrights
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->copyrights = $this->_copyrights->getCopyrightsAdmin($this->getParam('page'));
    }

    /** Add a new copyright topic
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new CopyrightsForm();
        $form->submit->setLabel('Add new copyright to system');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->_copyrights->add($form->getValues());
                $this->getFlash()->addMessage('Copyright created');
                $this->redirect('/admin/copyrights');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a copyright
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
        if ($this->getParam('id', false)) {
            $form = new CopyrightsForm();
            $form->submit->setLabel('Submit changes');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())
            ) {
                if ($form->isValid($form->getValues())) {
                    $where = array();
                    $where[] = $this->_copyrights->getAdapter()->quoteInto('id = ?',
                        $this->getParam('id'));
                    $this->_copyrights->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('You updated: <em>'
                        . $form->getValue('copyright')
                        . '</em> successfully. It is now available for use.');
                    $this->redirect('admin/copyrights/');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $form->populate($this->_copyrights->fetchRow('id= '
                    . $this->getParam('id'))->toArray());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a copyright
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
                $this->_copyrights->delete($where);
                $this->getFlash()->addMessage('Copyright attribution deleted!');
            }
            $this->redirect('/admin/copyrights/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->copyright = $this->_copyrights->fetchRow('id=' . $id);
            }
        }
    }

}