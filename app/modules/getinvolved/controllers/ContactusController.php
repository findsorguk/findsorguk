<?php
/** An action controller for handling the submission of comments and questions
 * @package Pas_Controller
 * @subpackage Action_Admin
 * @category Pas
 * @copyright DEJ Pett & British Museum
 * @license GNU Public
 * @author Daniel Pett
 * @version 1
 * @since 13th January 2011
 *
 */
class GetInvolved_ContactUsController extends Pas_Controller_Action_Admin {

	/** Initialise controller
	 *
	 */
    public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	}

	/** Method to submit a contact us comment to the Scheme
	 *
	 */
	public function indexAction() {
	$form = new ContactUsForm();
        $form->removeElement('captcha');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
        if ($form->isValid($form->getValues())) {
	$insertData = $form->getValues();
	$messages = new Messages();
        $messages->addComplaint($insertData);
	$cc = array();
	$cc[] = array(
		'email' => $form->getvalue('comment_author_email'),
		'name' => $form->getValue('comment_author')
		);
	$this->_helper->mailer($insertData, 'contactUs', null, $cc, $cc);
	$this->_flashMessenger->addMessage('Your enquiry has been submitted to the Scheme');
	$this->_redirect('getinvolved/contactus/');
	} else {
	$this->_flashMessenger->addMessage('There are problems with your submission');
	$form->populate($form->getValues());
	}
	}
	}



}