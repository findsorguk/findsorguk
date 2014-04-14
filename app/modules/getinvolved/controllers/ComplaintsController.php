<?php
/** Controller for getting complaints based form and submitting it
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_ComplaintsController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/
    public function init() {
		$this->_helper->acl->allow('public',null);
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}

	/** Submit complaints action
	*/
	public function indexAction() {
	$form = new ComplaintsForm();
        $form->removeElement('captcha');
	$this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $insertData = $form->getValues();
	$cc = array();
	$cc[] = array('email' => $form->getvalue('comment_author_email'),'name' => $form->getValue('comment_author'));
	$this->_helper->mailer($insertData, 'complaint', null, $cc, $cc );
	$messages = new Messages();
	$insert = $messages->addComplaint($insertData);
	$this->_flashMessenger->addMessage('Your complaint has been submitted');
	$this->_redirect('getinvolved/complaints/');
	} else {
	$this->_flashMessenger->addMessage('There are problems with your submission');
	$form->populate($form->getValues());
	}
	}
	}
}