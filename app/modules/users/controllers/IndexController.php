<?php

/** Controller for user module index and login
 *
 * @author     Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    1
 * @uses       Zend_Auth
 * @uses       Zend_Filter
 * @uses       LoginForm
 * @uses       Zend_Auth_Adapter_DbTable
 * @uses       Zend_Config
 * @uses       Zend_Registry
 */
class Users_IndexController extends Pas_Controller_Action_Admin
{

    /** The auth instance
     *
     * @access protected
     * @var \Zend_Auth
     */
    protected $_auth;

    /** Set up the ACL and contexts
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);
        $this->_auth = Zend_Auth::getInstance();
    }

    /** The redirect
     */
    const REDIRECT = '/users/account/';

    /** Creation of the login page
     *
     * @access public
     * @return void
     */
    public function indexAction()
    {
        if (null === $this->_auth->getIdentity()) {
            $form = new LoginForm();
            $this->view->form = $form;
            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
                $recap = $form->getvalue('g-recaptcha-response');
                $captcha = $form->getvalue('captcha');
                unset($recap);
                unset($captcha);

                $authAdapter = $form->username->getValidator('Authorise')->getAuthAdapter();
                $data = $authAdapter->getResultRowObject(null, 'password');
                $this->_auth->getStorage()->write($data);
                $this->redirect($this->_helper->loginRedirect());
            } else {
                $this->_auth->clearIdentity();
//                $this->getFlash()->addMessage('Sorry, there was a
//                        problem with your submission. Please check and try again');
                $form->populate($this->_request->getPost());
            }
        } else {
            $this->redirect(self::REDIRECT);
        }
    }

    /** Can't remember why this function exists
     */
    public function privilegesAction()
    {
        $this->_forward('index');
    }

    /** Identify the user
     *
     * @access public
     * @return void
     */
    public function identifyAction()
    {
        if ($this->getRequest()->isPost()) {
            $formData = $this->_getFormData();
            if (empty($formData['username']) || empty($formData['password'])) {
                $this->getFlash()->addMessage('Please provide a username and password.');
            } else {
                // do the authentication
                $authAdapter = $this->_getAuthAdapter($formData);
                $result = $this->_auth->authenticate($authAdapter);
                if (!$result->isValid()) {
                    $this->getFlash()->addMessage('Login failed');
                } else {
                    $data = $authAdapter->getResultRowObject(null, 'password');
                    $this->_auth->getStorage()->write($data);
                    $this->redirect(self::REDIRECT);
                }
            }
        }
    }


    /** Retrieve the login form data from _POST
     *
     * @access protected
     * @return array
     */
    protected function _getFormData()
    {
        $data = array();
        $filterChain = new Zend_Filter;
        $filterChain->addFilter(new Zend_Filter_StripTags);
        $filterChain->addFilter(new Zend_Filter_StringTrim);
        $data['username'] = $filterChain->filter($this->getRequest()->getPost('username'));
        $data['password'] = $filterChain->filter($this->getRequest()->getPost('password'));
        return $data;
    }

    /**Set up the auth adapater for interaction with the database
     *
     * @return Zend_Auth_Adapter_DbTable
     */
    protected function _getAuthAdapter($formData)
    {
        $dbAdapter = Zend_Registry::get('db');
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('SHA1(?)');
        $salt = $this->_helper->config()->auth->salt;
        $password = $salt . $formData['password'];
        $authAdapter->setIdentity($formData['username']);
        $authAdapter->setCredential($password);
        return $authAdapter;
    }
}