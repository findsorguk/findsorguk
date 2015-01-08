<?php

/** Controller for adding and manipulating user roles
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses StaffRoles
 * @uses StaffRoleForm
 */
class Admin_RolesController extends Pas_Controller_Action_Admin
{

    /** The staff roles model
     * @access protected
     * @var \StaffRoles
     */
    protected $_staffroles;

    /** The redirect uri
     * @access protected
     * @var string
     */
    protected $_redirectUrl = 'admin/roles/';

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('flos', array('index'));
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
        $this->_staffroles = new StaffRoles();

    }

    /** Display the index page
     */
    public function indexAction()
    {
        $this->view->roles = $this->_staffroles->getValidRoles();
    }

    /** View a role's details
     * @access public
     * @return void
     */
    public function roleAction()
    {
        $this->view->roles = $this->_staffroles->getRole($this->getParam('id'));
        $this->view->members = $this->_staffroles->getMembers($this->getParam('id'));
    }

    /** Add a role
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new StaffRoleForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->_staffroles->add($form->getValues());
                $this->getFlash()->addMessage('A new staff role has been created.');
                $this->redirect($this->_redirectUrl);
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a role
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new StaffRoleForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $where = array();
                $where[] = $this->_staffroles->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $this->_staffroles->update($form->getValues(), $where);
                $this->getFlash()->addMessage($form->getValue('role') . '\'s details updated.');
                $this->redirect($this->_redirectUrl);
            } else {
                $form->populate($form->getValues());
            }
        } else {
            $form->populate($this->_staffroles->fetchRow('id=' . $this->getParam('id'))->toArray());
        }
    }

    /** Delete a role
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
                $this->_staffroles->delete($where);
            }
            $this->getFlash()->addMessage('Role information deleted! This cannot be undone.');
            $this->redirect($this->_redirectUrl);
        } else {
            $this->view->role = $this->_staffroles->fetchRow('id =' . $this->_request->getParam('id'));
        }
    }
}
