<?php
/** Controller for adding and manipulating quotes
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Quotes
 * @uses Pas_Exception_Param
 * @uses QuoteForm
 */
class Admin_QuotesController extends Pas_Controller_Action_Admin {

    /** The quotes model
     * @access protected
     * @var \Quotes
     */
    protected $_quotes;
	
    /** The redirect
     * 
     */
    const REDIRECT = '/admin/quotes/';
	
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_quotes = new Quotes();
        
    }
	
    /** List all the quotes
     * @access public
     * @return void
     */
    public function indexAction() {
	$this->view->quotes = $this->_quotes->getQuotesAdmin($this->_getParam('page'));
    }
    
    /** Add a new quote
     * @access public
     * @return void
     */
    public function addAction() {
	$form = new QuoteForm();
	$form->details->setLegend('Add a new quote or announcement');
	$form->submit->setLabel('Submit details');
	$this->view->form =$form;
	if($this->getRequest()->isPost() 
                && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->_quotes->add($form->getValues());
                $this->getFlash()->addMessage('New quote/announcement entered');
                $this->_redirect( self::REDIRECT );
            } else  {
                $form->populate($form->getValues());
            }
	}
    }
    /** Edit a quote
     * @access public
     * @return void
     */			
    public function editAction() {
        if($this->_getParam('id',false)) {
        $form = new QuoteForm();
        $form->details->setLegend('Edit quote/announcement details');
        $form->submit->setLabel('Submit changes');
        $this->view->form =$form;
        if($this->getRequest()->isPost() 
                && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $where = array();
                $where[] = $this->_quotes->getAdapter()->quoteInto('id = ?', 
                        $this->_getParam('id'));
                $update = $this->_quotes->update($form->getValues(),$where);
                $this->getFlash()->addMessage('Details updated!');
                $this->_redirect( self::REDIRECT );
                } else {
                    $form->populate($form->getValues());
                }
            } else {
            $form->populate($this->_quotes
                    ->fetchRow('id=' . $this->_request->getParam('id'))
                    ->toArray());
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a quote
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->_quotes->delete($where);
                $this->getFlash()->addMessage('Quote/announcement deleted!');
            }
            $this->_redirect( self::REDIRECT);
        }  else  {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->quote = $this->_quotes->fetchRow('id =' . $id);
            }
        }
    }
}