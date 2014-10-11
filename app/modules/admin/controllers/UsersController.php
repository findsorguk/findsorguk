<?php
/** Controller for administering users and accounts
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Users
 * @uses UserFilterForm
 * @uses Pas_ArrayFunctions
 * @uses People
 * @uses ResearchProjects
 * @uses EditAccountForm
 * @uses RejectUpgradeForm
 * @uses ApproveReject
 * 
 */
class Admin_UsersController extends Pas_Controller_Action_Admin {
    
    /** The users model
     * @access protected
     * @var \Users
     */
    protected $_users;
    
    /** The array functions class
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_arrayFunctions;
    
    /** Get the model for use
     * @access public 
     * @return \Users
     */
    public function getUsers() {
        $this->_users = new Users();
        return $this->_users;
    }
      
    /** Get the array function class
     *  @access public
     * @return \Pas_ArrayFunctions
     */
    public function getArrayFunctions() {
        $this->_arrayFunctions = new Pas_ArrayFunctions();
        return $this->_arrayFunctions;
    }

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        
    }

    /** Display a list of users in paginated format
    */
    public function indexAction() {
        $this->view->paginator = $this->getUsers()->getUsersAdmin($this->_getAllParams());
        $form = new UserFilterForm();
        $this->view->form = $form;
        $form->username->setValue($this->_getParam('username'));
        $form->fullname->setValue($this->_getParam('fullname'));
        $form->role->setValue($this->_getParam('role'));
        if ($this->_request->isPost() && !is_null($this->_getParam('submit'))) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $params = $this->getArrayFunctions()->array_cleanup($formData);
                $where = array();
                foreach($params as $key => $value) {
                    if(!is_null($value)){
                        $where[] = $key . '/' . urlencode(strip_tags($value));
                    }
                }
            $whereString = implode('/', $where);
            $query = $whereString;
            $this->redirect('admin/users/index/' . $query . '/');
            } else {
                $form->populate($formData);
            }
        }
    }
    /** View a user's account
     * @access public
     * @return void
     */
    public function accountAction() {
        if($this->_getParam('username',false)) {
            $this->view->users = $this->getUsers()->findUserAccount((string)$this->_getParam('username'));
        } else {
            throw new Pas_Exception_Param('Parameter not found');
        }
    }
    
    /** Edit a user's account
     * @access public
     * @return void 
     * @throws Pas_Exception_Param
     */
    public function editAction() {
        if($this->_getParam('id',false)) {
            $form = new EditAccountForm();
            $form->submit->setLabel('Edit account details');
            $form->removeElement('password');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $id = (int)$this->_getParam('id');
                    $updateData = array(
                        'username' => $form->getValue('username'),
                        'first_name' => $form->getValue('first_name'),
                        'last_name' => $form->getValue('last_name'),
                        'fullname' => $form->getValue('fullname'),
                        'email' => $form->getValue('email'),
                        'institution' => $form->getValue('institution'),
                        'role' => $form->getValue('role'),
                        'peopleID' => $form->getValue('peopleID'),
                        'updated' => $this->getTimeForForms(),
                        'updatedBy' => $this->getIdentityForForms(),
                        'preferred_name' => $form->getValue('preferred_name'),
                        'canRecord' => $form->getValue('canRecord')
                    );
                    $where = array();
                    $where[] = $this->getUsers()->getAdapter()->quoteInto('id = ?', $id);
                    $oldData = $this->getUsers()->fetchRow('id=' . $this->_getParam('id'))->toArray();
                    $this->getUsers()->update($updateData,$where);

                    $this->_helper->audit(
                            $updateData, 
                            $oldData, 
                            'UsersAudit', 
                            $this->_getParam('id'), 
                            $this->_getParam('id')
                            );
                    $this->getFlash()->addMessage('You updated: <em>' 
                            . $form->getValue('fullname')
                            . '</em> successfully.');
                    $$this->redirect(/admin/users/account/username/' . $form->getValue('username'));
                } else {
                $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $user = $this->getUsers()->fetchRow('id ='.$id);
                    if(count($user)) {
                        $data = $this->getUsers()->toArray();
                        if(isset($data['peopleID'])) {
                            $people = new People();
                            $person = $people->fetchRow($people->select(
                                    )->where('secuid = ?', $data['peopleID']));
                            if($person){
                                $person = $person->toArray();
                                $form->peopleID->setValue($person['secuid']);
                                $form->person->setValue($person['fullname']);
                            }
                        }
                        $form->populate($data);
                    } else {
                        throw new Pas_Exception_Param('No user account found with that id');
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param('No parameter found on url string');
        }
    }
    /** Add a new user
     * @access public
     * @return void
    */
    public function addAction() {
        $form = new EditAccountForm();
        $form->setLegend('New account: ');
        $form->submit->setLabel('Create account details');
        $form->username->addValidator('Db_NoRecordExists', false, 
                array(
                    'table' => 'users',
                    'field' => 'username'
                    ));
        $form->institution->setRequired(true);
        $form->role->setRequired(true);
        $form->institution->setRequired(true);
        $form->email->setRequired(true);
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $salt = $this->_helper->config()->auth->salt;
                $password = SHA1($salt . $form->getValue('password'));
                $insertData = array(
                    'username' => $form->getValue('username'),
                    'first_name' => $form->getValue('first_name'),
                    'last_name' => $form->getValue('last_name'),
                    'fullname' => $form->getValue('fullname'),
                    'imagedir' => 'images/' . $form->getValue('username'),
                    'email' => $form->getValue('email'),
                    'institution' => $form->getValue('institution'),
                    'role' => $form->getValue('role'),
                    'password' => $password,
                    'peopleID' => $form->getValue('peopleID')
                );
                $username = $form->getValue('username');
                $this->getUsers()->add($insertData);
                $directories = array(
                IMAGE_PATH . $username,
                IMAGE_PATH . $username . '/small/',
                IMAGE_PATH . $username . '/medium/',
                IMAGE_PATH . $username . '/display/',
                IMAGE_PATH . $username . '/zoom/'
                );

                foreach ($directories as $dir){
                    mkdir($dir, 0777);
                }
                $this->getFlash()->addMessage('You successfully added a new account');
                $t$this->redirect(admin/users/account/username/'
                        . $form->getValue('username'));
            } else {
                $form->populate($formData);
            }
        }
    }
    /** List people wanting an upgrade, paginated
     * @access public
     * @return void
     */
    public function upgradesAction() {
        $this->view->users = $this->getUsers()->getUpgrades($this->_getParam('page'));
    }
    
    /** Upgrade a user's account to research status
     * @access public
     * @return void
    */
    public function upgradeAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
            $form = new AcceptUpgradeForm();
            $form->role->removeMultiOption('admin');
            $this->view->form = $form;
            if($this->getRequest()->isPost()
            && $form->isValid($this->_request->getPost())){
                if ($form->isValid($form->getValues())) {
                    $approvalData = array(
                        'status' => 'approved',
                        'message' => $form->getValue('message'),
                        'createdBy' => $this->getIdentityForForms(),
                        'created' => $this->getTimeForForms()
                    );
                    if(($form->getValue('already') != 1) && ($form->getValue('insert') == 1)) {
                        $researchData = array(
                            'title' => $form->getValue('title'),
                            'investigator' => $form->getValue('fullname'),
                            'description' => $form->getValue('researchOutline'),
                            'level' => $form->getValue('level'),
                            'createdBy' => $this->getIdentityForForms(),
                            'created' => $this->getTimeForForms(),
                            'startDate'=> $form->getValue('startDate'),
                            'valid' => 1,
                            'endDate'=> $form->getValue('endDate')
                        );
                        $research = new ResearchProjects();
                        $research->insert($researchData);
                    }
                    $userData = array(
                        'higherLevel' => 0,
                        'updatedBy' => $this->getIdentityForForms(),
                        'created' => $this->getTimeForForms(),
                        'researchOutline' => $form->getValue('researchOutline')
                    );

                    $where = array();
                    $where[] = $this->getUsers()->getAdapter()->quoteInto('id = ?', $id);
                    $this->getUsers()->update($userData,$where);

                    $approvals = new ApproveReject();
                    $approvals->insert($approvalData);

                    $to = array(array(
                        'email' => $form->getValue('email') ,
                        'name' => $form->getValue('fullname')
                            ));
                    $this->_helper->mailer($form->getValues(), 'upgradeAccount', $to);
                    $this->getFlash()->addMessage('Account upgraded and project data entered');
                    $th$this->redirect(dmin/users/upgrades');
                } else {
                $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $user = $this->getUsers()->fetchRow('id ='.$id);
                    if(count($user)) {
                        $form->populate($user->toArray());
                    } else {
                        throw new Pas_Exception_Param('No user account found with that id', 404);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Reject a user's account
     * @access public
     * @return void
    */
    public function rejectAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
            $form = new RejectUpgradeForm();
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $fullname = $form->getValue('fullname');
                    $email = $form->getValue('email');
                    $userUpdateData = array();
                    $userUpdateData['updated'] = $this->getTimeForForms();
                    $userUpdateData['updatedBy'] = $this->getIdentityForForms();
                    $userUpdateData['higherLevel'] = 0;
                    $rejectData = array(
                        'status' => 'reject',
                        'message' => $form->getValue('message'),
                        'createdBy' => $this->getIdentityForForms(),
                        'created' => $this->getTimeForForms()
                    );
                    $where = array();
                    $where[] = $this->getUsers()->getAdapter()->quoteInto('id = ?', $id);
                    $this->getUsers()->update($userUpdateData,$where);

                    $approvals = new ApproveReject();
                    $approvals->insert($rejectData);
                    $message = $form->getValue('message');
                    $researchOutline = $form->getValue('researchOutline');
                    $role = $form->getValue('role');
                    $to = array(array(
                        'email' => $form->getValue('email') ,
                        'name' => $form->getValue('fullname'))
                    );
                    $this->_helper->mailer($form->getValues(), 'upgradeRejected', $to);
                    $this->getFlash()->addMessage('Account rejected');
                    $this->_redirect('/admin/users/upgrades');
                } else {
                    $form->populate($formData);
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $user = $this_>getUsers()->fetchRow('id ='.$id);
                    if(count($user)) {
                        $form->populate($user->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}