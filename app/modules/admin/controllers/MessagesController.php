<?php
/** Controller for replying  to contact us messages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_MessagesController extends Pas_Controller_Action_Admin {
	
	protected $_messages;
	
	protected $_replies;
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_messages = new Messages();
	$this->_replies = new Replies();
    }
	/** Display list of messages sent
	*/
	public function indexAction() 	{
	$this->view->params = $this->_getAllParams();
	$this->view->messages = $this->_messages->getMessages($this->_getAllParams());
	}
	/** Reply to a stored message
	*/	
	public function replyAction() {
	if($this->_getParam('id',false)) {
	$form = new MessageReplyForm();
	$form->submit->setLabel('Send reply');
	$this->view->form = $form;
	if($this->getRequest()->isPost() 
        && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $reply = array();
	$reply['messagetext'] = $form->getValue('messagetext');
	$reply['messageID'] = $this->_getParam('id');
	$data['replied'] = 1;
	$where =  $this->_messages->getAdapter()->quoteInto('id= ?', $this->_getParam('id'));
	$update = $this->_messages->update($data, $where);
	$replies = new Replies();
	$replies->add($reply);
	$contact = array(array(
	'email' => $form->getValue('comment_author_email'), 
	'name' => $form->getValue('comment_author')
	));
	$this->_helper->mailer($form->getValues(),'messageResponse', $contact, $contact);
	$this->_flashMessenger->addMessage('Message replied to.');
	$this->_redirect('/admin/messages/');
	} else {
	$this->_flashMessenger->addMessage('There is a problem with the form, please check and resubmit');
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$message = $this->_messages->fetchRow('id ='.$id);
	if($message) {
	$form->populate($message->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	public function deleteAction(){
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_messages->delete($where);
	$this->_flashMessenger->addMessage('Message deleted!');
	}
	$this->_redirect( '/admin/messages');
	}  else  {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->message = $this->_messages->fetchRow('id =' . $id);
	}
	}
	}
	
	}