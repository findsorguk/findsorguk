<?php

/** Controller for adding and manipulating institutional data
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Institutions
 * @uses InstitutionForm
 * @uses Users
 * @uses Pas_Exception_Param
 *
 */
class Admin_InstitutionsController extends Pas_Controller_Action_Admin
{

    /** Institutions
     * @access protected
     * @var \Institutions
     */
    protected $_institutions;

    /** The users model
     * @access public
     * @var \Users
     */
    protected $_users;

    /** The redirect uri
     * @access protected
     * @var string
     */
    protected $_redirectUrl = 'admin/';

    /** Get the model
     * @access public
     * @return \Users
     */
    public function getUsers()
    {
        $this->_users = new Users();
        return $this->_users;
    }

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $flosActions = array('index');
        $this->_helper->_acl->allow('admin', null);
        $this->_helper->_acl->allow('fa', $flosActions);
        $this->_institutions = new Institutions();

    }

    /** Display the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->insts = $this->_institutions->getValidInsts($this->getAllParams());
    }

    /** Add an institution
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new InstitutionForm();
        $form->details->setLegend('Add institution details: ');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->_institutions->add($form->getValues());
                $this->getFlash()->addMessage('A new recording institution has been created.');
                $this->redirect($this->_redirectUrl . 'institutions/');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit an institution
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new InstitutionForm();
        $form->details->setLegend('Edit institution details: ');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()
            && $form->isValid($this->_request->getPost())
        ) {
            if ($form->isValid($form->getValues())) {
                $where = array();
                $where[] = $this->_institutions->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $this->_institutions->update($form->getValues(), $where);
                $this->getFlash()->addMessage($form->getValue('institution') . '\'s details updated.');
                $this->redirect($this->_redirectUrl . 'institutions/');
            } else {
                $form->populate($form->getValues());
            }
        } else {
            // find id is expected in $params['id']
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $this->view->inst = $this->_institutions->fetchRow('id=' . $id)->toArray();
                $form->populate($this->_institutions->fetchRow('id=' . $id)->toArray());
            }
        }
    }

    /** View institutional details
     * @access public
     * @return void
     */
    public function institutionAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->inst = $this->_institutions->getInst($this->getParam('id'));
            $this->view->members = $this->getUsers()->getMembersInstitution($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}