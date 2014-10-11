<?php
/** Controller for displaying Roman articles within the coin guide
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses OnlineAccounts
 * @uses SocialAccountsForm
*/
class Users_SocialController extends Pas_Controller_Action_Admin {

    /** The social accounts model
     * @access protected
     * @var \OnlineAccounts
     */
    protected $_accounts;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow('member',null);
        $this->_accounts = new OnlineAccounts();
        
    }

    /** Display index pages for the individual
     * @return void
     * @access public
     */
    public function indexAction() {
	$this->view->services = $this->_accounts
                ->getAllAccounts( (int)$this->getIdentityForForms() );
    }
    /** Add a new account
     * @access public
     * @return void
     */
    public function addAction()	{
        $form = new SocialAccountsForm();
        $form->submit->setLabel('Submit profile');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $insertData = array(
                    'accountName' => $form->getValue('accountName'),
                    'account' => $form->getValue('account'),
                    'public' => $form->getValue('public'),
                    'userID' => $this->getIdentityForForms(),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                );
                $this->_accounts->insert($insertData);
                $this->getFlash()->addMessage('A new account has been added to your profile.');
                $this->redirect('/users/');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit one of your social media accounts
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction(){
        if($this->_getParam('id',false)) {
            $form = new SocialAccountsForm();
            $form->submit->setLabel('Save profile');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $updateData = array(
                        'accountName' => $form->getValue('accountName'),
                        'account' => $form->getValue('account'),
                        'public' => $form->getValue('public'),
                        'userID' => $this->getIdentityForForms(),
                    );
                    $where = array();
                    $where[] = $this->_accounts->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $where[] = $this->_accounts->getAdapter()->quoteInto('userID = ?',$this->getIdentityForForms());
                    $this->_accounts->update($updateData,$where);
                    $this->getFlash()->addMessage('Webservice details updated.');
                    $this->redirect('/users/');
                } else {
                    $form->populate($formData);
                }
            } else {
            // find id is expected in $params['id']
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $service = $this->_accounts->fetchRow('userID = '.$this->getIdentityForForms().' AND id='.$id);
                if(count($service)) {
                    $form->populate($service->toArray());
                } else {
                    throw new Pas_Exception_Param($this->_nothingFound, 404);
                }
            }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Delete an account from social media
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = array();
                $where[] = $this->_accounts->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                $where[] = $this->_accounts->getAdapter()->quoteInto('userID = ?',$this->getIdentityForForms());
                $this->_accounts->delete($where);
            }
            $this->_redirect('/users/');
            $this->getFlash()->addMessage('Social profile deleted!');
        } else  {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->service = $this->_accounts->fetchRow('id='.$id);
            }
        }
    }

}