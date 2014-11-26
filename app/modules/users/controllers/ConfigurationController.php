<?php
/** Controller for configuring which fields to copy.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses CopyFind
 * @uses CopyFindSpot
 * @uses CopyCoin
 * @uses LoginRedirect
 * @uses ConfigureFindCopyForm
 * @uses ConfigureFindSpotCopyForm
 * @uses ConfigureCoinCopyForm
 * @uses ConfigureLoginRedirectForm
*/
class Users_ConfigurationController extends Pas_Controller_Action_Admin {

    /** Setup the ACL
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->deny('public');
        $this->_helper->_acl->allow('member',null);
        
    }
    /** Setup the index display pages
     * @access public
     * @return void
    */
    public function indexAction() {
        //Get the fields from the finds form
        $finds = new CopyFind();
        $this->view->findFields = $finds->getConfig();
        //Get the fields from the findspot form
        $findspots = new CopyFindSpot();
        $this->view->findSpotFields = $findspots->getConfig();
        //Get the fields for the coin form
        $coins = new CopyCoin();
        $this->view->coinFields = $coins->getConfig();
        //Get the field for the login redirect page form
        $redirect = new LoginRedirect();
        $this->view->redirectUri = $redirect->getConfig();
        // Get the fields for the hoard form
        $hoards = new CopyHoards();
        $this->view->hoards = $hoards->getConfig();

    }

    /** Set up and configure which fields you will copy when copying finds
     * @access public
     * @return void
     */
    public function findAction() {
        $form = new ConfigureFindCopyForm();
        $this->view->form = $form;
        $copyFind = new CopyFind();
        $current = $copyFind->getConfig();
        $values = array();
            foreach($current as $cur){
                $values[$cur] = 1;
            }
        $form->populate($values);
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $copyFind->updateConfig($form->getValues());
                $this->getFlash()
                        ->addMessage('Copy last record fields for find table updated');
                $this->redirect('/users/configuration/');
            } else {
                $form->populate($values);
            }
        }
    }

    /** Set up and configure which findspot fields to copy when copying record
     * @access public
     * @return void
     */
    public function findspotAction() {
        $form = new ConfigureFindSpotCopyForm();
        $this->view->form = $form;
        $copyFindSpot = new CopyFindSpot();
        $current = $copyFindSpot->getConfig();
        $values = array();
        foreach($current as $cur){
            $values[$cur] = 1;
        }
        $form->populate($values);
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $copyFindSpot->updateConfig($form->getValues());
                $this->getFlash()
                        ->addMessage('Copy last record fields for findspot table updated');
                $this->redirect('/users/configuration/');
            } else {
                $form->populate($values);
            }
        }
    }

    /** Set up and configure the coin copying fields for cloning a record.
     * @access public
     * @return void
     */
    public function coinAction() {
        $form = new ConfigureCoinCopyForm();
        $this->view->form = $form;
        $copyCoin = new CopyCoin();
        $current = $copyCoin->getConfig();
        $values = array();
        //As each value is a checkbox and we need to set as checked use 1
        foreach($current as $cur){
            $values[$cur] = 1;
        }
        $form->populate($values);
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $copyCoin->updateConfig($form->getValues());
                $this->getFlash()
                        ->addMessage('Copy last record fields for coin table updated');
                $this->redirect('/users/configuration/');
            } else {
                $form->populate($values);
            }
        }
    }

    /** Set up and configure the coin copying fields for cloning a record.
     * @access public
     * @return void
     */
    public function hoardAction() {
        $form = new ConfigureHoardCopyForm();
        $this->view->form = $form;
        $copyCoin = new CopyHoards();
        $current = $copyCoin->getConfig();
        $values = array();
        //As each value is a checkbox and we need to set as checked use 1
        foreach($current as $cur){
            $values[$cur] = 1;
        }
        $form->populate($values);
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $copyCoin->updateConfig($form->getValues());
                $this->getFlash()
                    ->addMessage('Copy last record fields for hoards table updated');
                $this->redirect('/users/configuration/');
            } else {
                $form->populate($values);
            }
        }
    }

    /** Set up the redirect action for a user
     * @access public
     * @return void
     */
    public function redirectAction() {
        $form = new ConfigureLoginRedirectForm();
        $this->view->form = $form;
        $loginRedirect = new LoginRedirect();
        $current = $loginRedirect->getConfig();
        $currentUri = array_keys($current);
        $form->populate(array('uri' => $currentUri[0]));
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $loginRedirect->updateConfig($form->getValues());
                $this->getFlash()->addMessage('Page after logging in updated');
                $this->redirect('/users/configuration/');
            } else {
                $form->populate($current);
            }
        }
    }
}