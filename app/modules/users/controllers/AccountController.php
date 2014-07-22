<?php
/** Controller for accessing user account stuff
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Zend_Registry
 * @uses Users
 * @uses ProfileForm
 * @uses Pas_Exception
 * @uses ForgotUsernameForm
 * @uses ActivateForm
 * @uses LoginForm
 * @uses RegisterForm
 * @uses ChangePasswordForm
 * @uses ResetPasswordKeyForm
 * @uses AccountUpgradeForm
 * 
*/
class Users_AccountController extends Pas_Controller_Action_Admin {

    /** The auth class
     * @access protected
     * @var \Zend_Auth
     */
    protected $_auth;
    
    /** The users model
     * @access protected
     * @var \Users
     */
    protected $_users;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow('public',array(
            'forgotten', 'register', 'activate',
            'index', 'logout', 'edit',
            'forgotusername', 'success', 'resetpassword'
            ));
	$this->_helper->_acl->allow('member',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_auth = Zend_Registry::get('auth');
	$this->_users = new Users();
    }
    
    /** Set up index page
     * @access public
     * @return void
    */
    public function indexAction() {
        // If user isn't logged in, show login form
        if (is_null($this->_auth->getIdentity())) {
            $this->_helper->redirector->gotoRouteAndExit(
                    array(
                        'module' => 'users', 'controller' => 'index'
                        ));
        } else {
            $this->view->users = $this->_users->getUserProfile($this->getIdentityForForms());
        }
    }
    
    /** Logout and clear the identity from the storage
     * @access public
     * @return void
     */
    public function logoutAction() {
	$this->_auth->clearIdentity();
	$this->_flashMessenger->addMessage('You have now logged out');
	return $this->_redirect('/users/');
    }
    
    /** Edit the user details
     * @access public
     * @return void
     * @throws Pas_Exception
     */
    public function editAction() {
        $form = new ProfileForm();
        $form->removeElement('username');
        $form->removeElement('password');
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $where = array();
                $where[] = $this->_users->getAdapter()->quoteInto('id = ?', $this->getIdentityForForms());
                $this->_users->update($form->getValues(), $where);
                $this->_flashMessenger->addMessage('You updated your profile successfully.');
                $this->_redirect('/users/account/');
            } else {
                $form->populate($form->getValues());
                $this->_flashMessenger->addMessage('You have some errors with your submission.');
            }
        } else {
            $id = (int)$this->getIdentityForForms();
            if ($id > 0) {
                $user = $this->_users->fetchRow('id =' . $this->getIdentityForForms())->toArray();
                if($user) {
                    $form->populate($user);
                } else {
                    throw new Pas_Exception('No user account found with that id', 500);
                }
            }
        }
    }

    /** Retrieve the user's user name
     * @access public
     * @return void
     */
    public function forgotusernameAction() {
        if ($this->_auth->getIdentity()) {
            $this->_flashMessenger->addMessage('You are already logged in!');
            $this->_redirect('/users');
        } else {
            $form = new ForgotUsernameForm();
            $this->view->form = $form;
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $userData = $this->_users->getUserByUsername($form->getValue('email'));
                    $to = array(array(
                        'email' => $form->getValue('email'),
                        'name' => $userData[0]['fullname'])
                        );
                    $this->_helper->mailer($userData[0], 'forgottenUsername', $to);
                    $this->_flashMessenger->addMessage('Account reminder sent to your email address');
                    $this->_redirect('/users/');
                } else {
                    $this->_flashMessenger->addMessage('Problems have been found with your submission');
                    $form->populate($form->getValues());
                }
            }
        }
    }

    /** Retrieve a password for a user
     * @access public
     * @return void
     */
    public function forgottenAction() {
        if ($this->_auth->getIdentity()) {
            $this->_flashMessenger->addMessage('You are already logged in.');
            $this->_redirect('/users');
        }
        $form = new ForgotPasswordForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $results = $this->_users->findUser($form->getValue('email'), $form->getValue('username'));
                if($results) {
                    $length = 6;
                    $newKey = "";
                    // define possible characters
                    $possible = "0123456789bcdfghjkmnpqrstvwxyz";
                    $i = 0;
                    // add random characters to $password until $length is reached
                    while ($i < $length) {
                    // pick a random character from the possible ones
                        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
                        // we don't want this character if it's already in the password
                        if (!strstr($newKey, $char)) {
                            $newKey .= $char;
                            $i++;
                        }
                    }
                    $updatesdata = array (
                        'activationKey' =>  $newKey,
                        );
                    $to = array(array(
                        'email' => $form->getValue('email'), 
                        'name' => $results[0]['fullname']
                            ));
                    $assignData = array_merge(
                            $results[0],
                            array('activationKey' => $newKey)
                            ,$form->getValues()
                            );
                    $this->_helper->mailer($assignData, 'forgottenPassword', $to );
                    $where = array();
                    $where[] = $this->_users->getAdapter()
                            ->quoteInto('username = ?', (string)$form->getValue('username'));
                    $where[] = $this->_users->getAdapter()
                            ->quoteInto('email = ?', (string)$form->getValue('email'));
                    $this->_users->update($updatesdata, $where);
                    $assignData = array_merge($updatesdata,$form->getValues());
                    $this->_flashMessenger->addMessage('Please check your email');
                    $this->_redirect('/users/account/resetpassword');
                } else {
                    $this->_flashMessenger->addMessage('Either your email address/or username is incorrect.');
                }
            } else {
                $this->_flashMessenger->addMessage('You have not filled in the form correctly');
            }
        }
    }

    /** Register for an account
     * @access public
     * @return void
     */
    public function registerAction() {
        if($this->_auth->hasIdentity()) {
            $this->_flashMessenger->addMessage('You are already logged in and registered.');
            $this->_redirect('/users/account');
        } else {
            $salt = $this->_helper->config()->auth->salt;
            $form = new RegisterForm();
            $this->view->form = $form;
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
                $to = array(array(
                    'email' => $form->getValue('email'),
                    'name' => $form->getValue('first_name') . ' ' . $form->getValue('last_name'))
                    );
                $emailData = array(
                    'email' => $form->getValue('email'),
                    'name' => $form->getValue('first_name') . ' ' . $form->getValue('last_name'),
                    'activationKey' => md5($form->getValue('username') . $form->getValue('first_name'))
                    );
                $this->_users->register($form->getValues());
                $this->_helper->mailer($emailData, 'activateAccount', $to);
                $this->_flashMessenger->addMessage('Your account has been created. Please check your email.');
                $this->_redirect('/users/account/activate/');
                $form->populate($form->getValues());
                $this->_flashMessenger->addMessage('There are a few problems with your registration<br/>
        Please review and correct them.');
            }
        }
    }

    /** Activate an account
     * @access public
     * @return void
     */
    public function activateAction(){
        if (!is_null($this->_auth->getIdentity())) {
            $this->_redirect('users/account/');
        }
        $form = new ActivateForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $this->_users->activate($form->getValues());
                $this->_flashMessenger->addMessage('Your account has been activated.');
                $this->_redirect('users/account/success/');
            } else {
                $form->populate($form->getValues());
                $this->_flashMessenger->addMessage('Please review and correct problems');
            }
        }
    }

    /** On success action
     * @access public
     * @return void
     */
    public function successAction(){
        if(null === $this->_auth->getIdentity()) {
            $this->view->headTitle('Login to the system');
            $form = new LoginForm();
            $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $authAdapter = $form->username->getValidator('Authorise')->getAuthAdapter();
                $data = $authAdapter->getResultRowObject(NULL,'password');
                $this->_auth->getStorage()->write($data);
                $this->_redirect( $this->_helper->loginRedirect() );
            } else {
                $this->_auth->clearIdentity();
                $this->_flashMessenger->addMessage('Sorry, there was a problem with your submission. 
                Please check and try again');
                $form->populate($formData);
            }
        } 
        } else {
            $this->_redirect('/users/');
        }
    }
    /** List user's logins
     * @access public
     * @return void
    */
    public function loginsAction() {
        $logins = new Logins();
        $this->view->logins = $logins->myLogins($this->getUsername(),$this->_getParam('page'));
        $this->view->ips = $logins->myIps($this->getUsername());
    }


    /** Change a password
     * @access public
     * @return void
     */
    public function changepasswordAction() {
        $form = new ChangePasswordForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $password = SHA1($this->_helper->config()->auth->salt . $form->getValue('password'));
                $where = array();
                $where[] = $this->_users->getAdapter()->quoteInto('id = ?', $this->getIdentityForForms());
                $this->_users->update(array('password' => $password), $where);
                $this->_flashMessenger->addMessage('You have changed your password');
                $this->_redirect('/users/account/');
            } else {
                $form->populate($form->getValues());
            }
        }
    }
    /** Upgrade an account
     * @access public
     * @return void
     */
    public function upgradeAction() {
        $allowed = array('public','member');
        if(in_array($this->getRole(), $allowed)) {
            $user = $this->getAccount();
            $form = new AccountUpgradeForm();
            $this->view->form = $form;
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $where = array();
                    $where[] =  $this->_users->getAdapter()->quoteInto('id = ?', (int)$this->getAccount()->id);
                    $updateData = $form->getValues();
                    $updateData['higherLevel'] = 1;
                    $update = $this->_users->update($updateData, $where);
                    $to = array(array('email' => $user->email, 'name' => $user->fullname));
                    $attachments = array( ROOT_PATH . '/public_html/documents/tac.pdf' );
                    $assignData = array_merge($to[0], $form->getValues());
                    $this->_helper->mailer($assignData, 'upgradeRequested', null, $to, $to, null, $attachments);
                    $this->_flashMessenger->addMessage('Thank you! We have received your request.');
                    $this->_redirect('/users/account/');
                } else {
                    $form->populate($form->getValues());
                    $this->_flashMessenger->addMessage('There are a few problems with your registration<br>
                    Please review and correct them.');
                }
            }
        } else {
            $this->_flashMessenger->addMessage('You can\'t request an upgrade as you already have ' . $role . ' status!');
            $this->_redirect('/users/account/');
        }
    }

    /** Configure the copy action
     * @todo Is this needed?
     * @access public
     * @return void
     */
    public function configurecopyAction(){
        //View only
    }

    /** Reset a password
     * @access public
     * @return void
     */
    public function resetpasswordAction(){
        if (!is_null($this->_auth->getIdentity())) {
            $this->_redirect('users/account/');
        }
        $form = new ResetPasswordKeyForm();
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            $this->_users->resetPassword($form->getValues());
            $this->_flashMessenger->addMessage('Your password has been reset.');
            $this->_redirect('users/account/success/');
        }
        else {
            $form->populate($form->getValues());
            $this->_flashMessenger->addMessage('Please review and correct problems');
        }	
    }
}
