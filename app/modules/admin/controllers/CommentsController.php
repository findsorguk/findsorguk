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
class Admin_CommentsController extends Pas_Controller_Action_Admin
{

    /** The comments model
     * @access protected
     * @var \Comments
     */
    protected $_comments;

    /** Get the comments model
     * @return Comments
     */
    public function getComments()
    {
        $this->_comments = new Comments();
        return $this->_comments;
    }

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
    }

    /** Display all the comments
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->params = $this->getAllParams();
        $this->view->comments = $this->getComments()->getComments($this->getAllParams());
    }

    /** Publish a comment
     * @access public
     * @throws Exception
     * @throws Pas_Exception_Param
     * @return void
     */
    public function publishAction()
    {
        if ($this->getParam('id', false)) {
            $form = new PublishCommentFindForm();
            $form->submit->setLabel('Submit changes');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $data = $form->getValues();
                    $to[] = array(
                        'name' => $form->getValue('comment_author'),
                        'email' => $form->getValue('comment_author_email'));
                    $where = $this->getComments()->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $this->getComments()->update($data, $where);

                    $this->_helper->mailer($form->getValues(), 'commentPublished', $to);
                    $this->getFlash()->addMessage('Comment data updated.');
                    $this->redirect('/admin/comments/');
                } else {
                    $this->getFlash()->addMessage('There is a problem with the form, please check and resubmit');
                    $form->populate($this->_request->getPost());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $comment = $this->getComments()->fetchRow($this->getComments()->select()->where('id = ?', $id))->toArray();
                    if ($comment) {
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