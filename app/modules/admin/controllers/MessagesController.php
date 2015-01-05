<?php

/** Controller for replying  to contact us messages
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Message
 * @uses Replies
 * @uses MessageReplyForm
 * @uses Pas_Exception_Param
 */
class Admin_MessagesController extends Pas_Controller_Action_Admin
{

    /** The messages model
     * @access protected
     * @var \Messages
     */
    protected $_messages;

    /** The replies model
     * @access protected
     * @var \Replies
     */
    protected $_replies;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
        $this->_messages = new Messages();
        $this->_replies = new Replies();

    }

    /** Display list of messages sent
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->params = $this->getAllParams();
        $this->view->messages = $this->_messages->getMessages($this->getAllParams());
    }

    /** Reply to a stored message
     * @access public
     * @return void
     */
    public function replyAction()
    {
        if ($this->getParam('id', false)) {
            $form = new MessageReplyForm();
            $form->submit->setLabel('Send reply');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())
            ) {
                if ($form->isValid($form->getValues())) {
                    $reply = array();
                    $reply['messagetext'] = $form->getValue('messagetext');
                    $reply['messageID'] = $this->getParam('id');
                    $data['replied'] = 1;
                    $where = $this->_messages->getAdapter()->quoteInto('id= ?',
                        $this->getParam('id'));
                    $update = $this->_messages->update($data, $where);
                    $this->_replies->add($reply);
                    $contact = array(array(
                        'email' => $form->getValue('comment_author_email'),
                        'name' => $form->getValue('comment_author')
                    ));
                    $this->_helper->mailer($form->getValues(), 'messageResponse',
                        $contact, $contact);
                    $this->getFlash()->addMessage('Message replied to.');
                    $this->redirect('/admin/messages/');
                } else {
                    $this->getFlash()->addMessage('There is a problem with '
                        . 'the form, please check and resubmit');
                    $form->populate($form->getValues());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $message = $this->_messages->fetchRow('id =' . $id);
                    if ($message) {
                        $form->populate($message->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a message
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
                $this->_messages->delete($where);
                $this->getFlash()->addMessage('Message deleted!');
            }
            $this->redirect('/admin/messages');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->message = $this->_messages->fetchRow('id =' . $id);
            }
        }
    }
}