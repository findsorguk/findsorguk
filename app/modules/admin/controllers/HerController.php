<?php
/** Controller for setting up and manipulating historic environment data sign ups
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category  Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Hers
 * @uses HerForm
 */
class Admin_HerController extends Pas_Controller_Action_Admin {

    /** The hers model
     * @access protected
     * @var \Hers
     */
    protected $_hers;
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        $this->_hers = new Hers();
        
    }
    /** Set up the index action
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->hers = $this->_hers->getAll($this->_getAllParams());
    }
    
    /** Add a signatory
     * @access public
     * @return void
     */
    public function addAction() {
        $form = new HerForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() 
                && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $this->_hers->add($form->getValues());
                $this->getFlash()->addMessage('A new HER signatory has been created.');
                $this->redirect('/admin/her/');
            } else {
                $form->populate($form->getValues());
            }
        }
    }
    /** Edit a signatory
     * @access public
     * @return void
     */			
    public function editAction() {
        $form = new HerForm();
        $form->submit->setLabel('Submit HER details change');
        $this->view->form = $form;
        if($this->getRequest()->isPost() 
                && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $where = array();
                $where[] =  $this->_hers->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                $this->_hers->update($form->getValues(),$where);
                $this->getFlash()->addMessage($form->getValue('name') . '\'s details updated.');
                $this->redirect('/admin/her/');
            } else {
                $form->populate($form->getValues());
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $form->populate($this->_hers->fetchRow('id=' . $id)->toArray());
            }
        }
    }
}