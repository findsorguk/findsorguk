<?php

/**  An action controller for handling the submission of comments and questions
 *
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @category Pas
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @since 13th January 2011
 * @uses ContactUsForm
 * @uses Messages
 *
 */
class About_ContactUsController extends Pas_Controller_Action_Admin
{
    protected $pasMailboxEmail;
    protected $pasMailboxEmailName;

    /** Initialize the acl helper and messenger
     * @access public
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
        $this->pasMailboxEmail = Zend_Registry::get('config')->pasmailbox->email;
        $this->pasMailboxEmailName = Zend_Registry::get('config')->pasmailbox->name;
    }

    /** The action that allows people to send in requests via the contact us
     * form
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $form = new ContactUsForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $recap = $form->getvalue('g-recaptcha-response');
                $captcha = $form->getvalue('captcha');
                unset($recap);
                unset($captcha);

                $messages = new Messages();
                $messages->addComplaint($form->getValues());

                //Use a different to address than default, which is the sender email. However, if not set, fall back
                //to sender address by not setting this array.
                $to = null;
                if($this->pasMailboxEmail && $this->pasMailboxEmailName) {
                    $to[] = array(
                        'email' => $this->pasMailboxEmail,
                        'name' => $this->pasMailboxEmailName
                    );
                }
                $cc = array();
                $cc[] = array(
                    'email' => $form->getvalue('comment_author_email'),
                    'name' => $form->getValue('comment_author')
                );
                $this->_helper->mailer($form->getValues(), 'contactUs', $to, $cc);
                $this->getFlash()->addMessage('Your enquiry has been submitted to the Scheme');
                $this->redirect('about/contactus/');
            } else {
                $this->getFlash()->addMessage('There are problems with your submission');
                $form->populate($form->getValues());
            }
        }
    }
}
