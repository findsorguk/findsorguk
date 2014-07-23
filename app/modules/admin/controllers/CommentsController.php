<?php
/** Controller for manipulating comments
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Comments
 * @uses PublishCommentFindForm
*/
class Admin_CommentsController extends Pas_Controller_Action_Admin {

    /** The comments model
     * @access protected
     * @var \Comments
     */
    protected $_comments;
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_comments = new Comments();
    }

    /** Display all the comments
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->params = $this->_getAllParams();
        $this->view->comments = $this->_comments->getComments($this->_getAllParams());
    }

    /** Publish a comment
     * @access public
     * @throws Exception
     * @throws Pas_Exception_Param
     * @return void
     */
    public function publishAction()	{
        if($this->_getParam('id',false)) {
            $form = new PublishCommentFindForm();
            $form->submit->setLabel('Submit changes');
            $this->view->form = $form;
            if($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $data = $form->getValues();
                    $to[] = array(
                    'name' => $form->getValue('comment_author'), 
                    'email' => $form->getValue('comment_author_email'));
                    $where =  $this->_comments->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $this->_comments->update($data,$where);

                    $this->_helper->mailer($form->getValues(),'commentPublished', $to);
                    $this->_flashMessenger->addMessage('Comment data updated.');
                    $this->_redirect('/admin/comments/');
                } else {
                    $this->_flashMessenger->addMessage('There is a problem with the form, please check and resubmit');
                    $form->populate($data);
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $comment = $this->_comments
                            ->fetchRow($this->_comments->select()->where('id = ?', $id))->toArray();
                    if($comment) {
                    $form->populate($comment);
                    } else {
                        throw new Exception('No comment found with that ID');
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}