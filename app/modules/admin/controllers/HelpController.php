<?php
/** Controller for setting up and manipulating help topics
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Help
 * @uses HelpForm
 * @uses Pas_Exception_Param
 * 
 */
class Admin_HelpController extends Pas_Controller_Action_Admin {

    /** The help model
     * @access protected
     * @var \Help
     */
    protected $_help;
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        
        $this->_help = new Help();
        
    }
    
    /** Set up the index of help topics
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->contents = $this->_help->getContentAdmin($this->_getParam('page'));
    }
    
    /** Add a new help topic
     * @access public
     * @return void
     */
    public function addAction() {
        $form = new HelpForm();
        $form->submit->setLabel('Add new help topic to system');
        $form->author->setValue($this->getIdentityForForms());
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $this->_help->add($form->getValues());
                $this->getFlash()->addMessage('Help topic has been created!');
                $this->_redirect('/admin/help');
            } else  {
                $form->populate($form->getValues());
            }
        }
    }
    /** Edit a help topic
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction(){
        if($this->_getParam('id',false)){
        $form = new HelpForm();
        $form->submit->setLabel('Submit changes');
        $form->author->setValue($this->getIdentityForForms());
        $this->view->form = $form;
        if($this->getRequest()->isPost() 
                && $form->isValid($this->_request->getPost())){
        if ($form->isValid($form->getValues())) {
            $where = array();
            $where[] = $this->_help->getAdapter()->quoteInto('id = ?', 
                    $this->_getParam('id'));
            $this->_help->update($form->getValues(),$where);
            $this->getFlash()->addMessage('You updated: <em>' 
                    . $form->getValue('title')
                    . '</em> successfully. It is now available for use.');
            $this->_redirect('admin/help/');
        } else {
            $form->populate($form->getValues());
        }
        } else {
            $form->populate($this->_help->fetchRow('id= ' 
                    . $this->_getParam('id'))->toArray());
        }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete a help topic
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->_help->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
            }
            $this->_redirect('/admin/help/');
        } else  {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->content = $this->_help->fetchRow('id=' . $id);
            }
        }
    }

}