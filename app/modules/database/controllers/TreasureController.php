<?php
/** Controller for treasure module
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses TreasureCases
 * @uses Pas_Exception_Param
 * @uses TreasureValuations
 * @uses TreasureAssignations
 * @uses TvcDatesToCases
 * @uses TreasureActions
 * @uses AgreedTreasureValuations
 * @uses ProvisionalValuationForm
 *
 */
class Database_TreasureController extends Pas_Controller_Action_Admin
{

    /** The treasure ID to query
     * @access protected
     * @var string
     */
    protected $_treasureID;

    /** Get the treasure ID from the url params
     * @access public
     * @return string
     */
    public function getTreasureID()
    {
        $this->_treasureID = $this->getParam('treasureID');
        return $this->_treasureID;
    }

    /** The redirect to use
     * @access protected
     * @var string
     */
    protected $_redirect;

    /** Get the redirect
     * @access public
     * @return string
     */
    public function getRedirect()
    {
        $this->_redirect = $this->view->url(
            array(
                'module' => 'database',
                'controller' => 'treasure',
                'action' => 'casehistory',
                'treasureID' => $this->_treasureID)
            , null, true);
        return $this->_redirect;
    }

    /** The init function
     * @access public
     * @return void
     */
    public function init()
    {

        $this->_helper->_acl->allow('flos', null);
        $this->view->id = $this->_treasureID;

    }

    /** Index action - nothing here
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('There is no direct access to the root action for treasure');
        $this->redirect('/treasure/cases/');
    }

    /** Case history for a Treasure ID
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function casehistoryAction()
    {
        if ($this->getParam('treasureID', false)) {
            $treasure = new TreasureCases();
            $this->view->cases = $treasure->getCaseHistory($this->_treasureID);
            $valuations = new TreasureValuations();
            $this->view->values = $valuations->listvaluations($this->_treasureID);
            $curators = new TreasureAssignations();
            $this->view->curators = $curators->listCurators($this->_treasureID);
            $committees = new TvcDatesToCases();
            $this->view->tvcs = $committees->listDates($this->_treasureID);
            $actions = new TreasureActions();
            $this->view->actions = $actions->getActionsListed($this->_treasureID);
            $finals = new AgreedTreasureValuations();
            $this->view->finalvalues = $finals->listvaluations($this->_treasureID);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** The event action related to the treasure case
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     * @todo finish this
     */
    public function eventAction()
    {
        if ($this->getParam('treasureID', false)) {

        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit an event
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     * @todo finish this
     */
    public function editeventAction()
    {
        if ($this->getParam('treasureID', false)) {

        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Enter a provisional value
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function provisionalvalueAction()
    {
        if ($this->getParam('treasureID', false)) {
            $form = new ProvisionalValuationForm();
            $form->submit->setLabel('Add valuation');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $data = $form->getValues();
                    $provisionals = new TreasureValuations();
                    $provisionals->add($data);
                    $this->redirect($this->_redirect);
                    $this->getFlash()->addMessage('A new provisional value has been added.');
                } else {
                    $form->populate($formData);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit a valuation
     * @access public
     * @return void
     */
    public function editprovisionalvalueAction()
    {
        $form = new ProvisionalValuationForm();
        $form->submit->setLabel('Change valuation');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $provisionals = new TreasureValuations();
                $provisionals->updateTreasure($data);
                $this->redirect($this->_redirect);
                $this->getFlash()->addMessage('A provisional value has been updated.');
            } else {
                $form->populate($formData);
            }
        } else {
            $provisionals = new TreasureValuations();
            $edit = $provisionals->fetchRow($provisionals->select()->where('treasureID = ?', $this->_treasureID));
            $form->populate($edit->toArray());
        }
    }

    /** Assign a curator
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function assigncuratorAction()
    {
        if ($this->getParam('treasureID', false)) {
            $form = new TreasureAssignForm();
            $form->submit->setLabel('Assign to curator');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $data = $form->getValues();
                    $curators = new TreasureAssignations();
                    $curators->add($data);
                    $this->redirect($this->_redirect);
                    $this->getFlash()->addMessage('Curator has been assigned.');
                } else {
                    $form->populate($formData);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** The TVC action
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function tvcAction()
    {
        if ($this->getParam('treasureID', false)) {
            $form = new TVCDateForm();
            $form->submit->setLabel('Assign to meeting date');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $data = $form->getValues();
                    $dates = new TvcDatesToCases();
                    $dates->add($data);
                    $this->redirect($this->_redirect);
                    $this->getFlash()->addMessage('Curator has been assigned.');
                } else {
                    $form->populate($formData);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Enter final valuation
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function finalAction()
    {
        if ($this->getParam('treasureID', false)) {
            $form = new FinalValuationForm();
            $form->submit->setLabel('Add final valuation');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $data = $form->getValues();
                    $provisionals = new AgreedTreasureValuations();
                    $provisionals->add($data);
                    $this->redirect($this->_redirect);
                    $this->getFlash()->addMessage('A new final valuation has been added.');
                } else {
                    $form->populate($formData);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}


