<?php
/** Controller for user module index and login
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_IndexController extends Pas_Controller_Action_Admin {
	
	protected $_auth;
	/** Set up the ACL and contexts
	*/			
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_auth = Zend_Auth::getInstance();
    }

    const REDIRECT = '/users/account/';
	/** Creation of the login page
	*/			
    public function indexAction() {
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
	$this->_redirect(self::REDIRECT);
	} else {
	$this->_auth->clearIdentity();
	$this->_flashMessenger->addMessage('Sorry, there was a problem with your submission. 
	Please check and try again');
	$form->populate($formData);
	}
	} 
	} else {
	$this->_redirect(self::REDIRECT);
	}
    }
	
	/** Can't remember why this function exists
	*/		
	public function privilegesAction() {
	$this->_forward('index');
    }
	/** identify the user
	*/		
	public function identifyAction()  {
	if ($this->getRequest()->isPost()) {
	$formData = $this->_getFormData();
	if (empty($formData['username']) || empty($formData['password'])) {
	$this->_flashMessenger->addMessage('Please provide a username and password.');
	} else {
	// do the authentication
	$authAdapter = $this->_getAuthAdapter($formData);
	$result = $this->_auth->authenticate($authAdapter);
	if (!$result->isValid()) {
	$this->_flashMessenger->addMessage('Login failed');
	} else {
	$data = $authAdapter->getResultRowObject(null, 'password');
	$this->_auth->getStorage()->write($data);
	$this->_redirect(self::REDIRECT);
	return;
	}
	}
	}
//	$this->_redirect($this->view->baseUrl().'/users/');
    }
    
    
    /**
     * Retrieve the login form data from _POST
     *
     * @return array
     */
	protected function _getFormData() {
	$data = array();
	$filterChain = new Zend_Filter;
	$filterChain->addFilter(new Zend_Filter_StripTags);
	$filterChain->addFilter(new Zend_Filter_StringTrim);
	$data['username'] = $filterChain->filter($this->getRequest()->getPost('username'));
	$data['password'] = $filterChain->filter($this->getRequest()->getPost('password'));
    return $data;
    }
    
    /**
     * Set up the auth adapater for interaction with the database
     *
     * @return Zend_Auth_Adapter_DbTable
     */
	protected function _getAuthAdapter($formData)  {
	$dbAdapter = Zend_Registry::get('db');
	$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
	$authAdapter->setTableName('users')
		->setIdentityColumn('username')
		->setCredentialColumn('password')
		->setCredentialTreatment('SHA1(?)');
	// get "salt" for better security
	$config = Zend_Registry::get('config');
	$salt = $config->auth->salt;
	$password = $salt.$formData['password'];
	$authAdapter->setIdentity($formData['username']);
	$authAdapter->setCredential($password);
	return $authAdapter;
	}
	
}