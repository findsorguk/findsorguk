<?php

/** Controller for CRUD of references on database
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Bibliography
 * @uses ReferenceFindForm
 * @uses Pas_Exception_Param
 * @uses Publications
 */
class Database_ReferencesController extends Pas_Controller_Action_Admin
{
    /** Get the bibliography model
     * @access public
     * @return \Finds
     */
    public function getBibliography()
    {
        return new Bibliography();
    }

    /** Initialise the ACL and contexts
     */
    public function init()
    {
        $publicActions = array('index');
        $this->_helper->_acl->allow('flos', null);
        $this->_helper->_acl->allow('member', array('add', 'edit', 'delete'));
        $this->_helper->_acl->allow('public', $publicActions);
        $this->setController($this->getParam('recordtype', 'artefacts'));
        $this->setRedirect($this->getController());
    }

    /** Constant for redirect url
     */
    const REDIRECT = 'database/artefacts/record/id/';

    /** Constant for redirect to reference
     */
    const REFERENCE = '#references';

    /** The controller to redirect to on completion of action
     * @access protected
     * @var \Findspots
     */
    protected $_controller;

    /** The redirect URL to go to on completion of action
     * @access protected
     * @var
     */
    protected $_redirect;

    /** Set the controller to redirect to on completion of action
     * @access public
     * @param string $recordtype
     * @return
     */
    public function setController($recordtype)
    {
        $this->_controller = $recordtype;
        return $this;
    }

    /** Set the redirect URL to go to on completion of action
     * @access public
     * @return
     */
    public function setRedirect($controller)
    {
        $module = '/database/';
        $this->_redirect = $module . $controller . '/';
        return $this;
    }

    /** Get the controller to redirect to on completion of action
     * @access public
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /** Get the redirect URL to go to on completion of action
     * @access public
     * @return string
     */
    public function getRedirect()
    {
        return $this->_redirect;
    }

    /** No direct access to the references controller, redirect applied.
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('No access to root file for reference');
        $this->getResponse()->setHttpResponseCode(301)->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('/database/publications');
    }


    /** Adding a reference
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new ReferenceFindForm();
        $this->view->form = $form;

	$this->checkIfReferenceExists($this->getParam('copy'), $form);

        if ($this->_request->isPost())
	{
            $formData = $this->_request->getPost();

            if ($form->isValid($formData))
	    {
		$this->addReference($form->getValues());
                $this->getFlash()->addMessage('A new reference work has been added to this record');
                $this->redirect($this->getRedirect() . 'record/id/' . $this->getParam('findID') . self::REFERENCE);
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit a reference entity
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new ReferenceFindForm();
        $this->view->form = $form;
	$id = (int)$this->_request->getParam('id', 0);
	$findID = (int)$this->getParam('findID');
	$controllerName = $this->getController();

        if (!(ctype_digit($id) && ($id > 0)))
        {
            $this->redirect($this->getRedirect() . 'record/id/' . $findID);
        }

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();

            if ($form->isValid($formData))
	    {
                unset($formData['authors']);
                unset($formData['submit']);

		// Update record
                $where = array();
                $where[] = $this->getBibliography()->getAdapter()->quoteInto('id = ?', $id);
                $this->getBibliography()->update($formData, $where);

                $this->getFlash()->addMessage('Reference details updated!');
		$this->getBibliography()->clearCacheEntry($id, $controllerName);
                $this->redirect($this->getRedirect() . 'record/id/' . $findID . self::REFERENCE);
            } else
	    {
                $form->populate($formData);
            }
        } else
	{
            $pubs = new Publications();
	    $this->populateForm($pubs, $this->getBibliography()->fetchFindBook($id, $controllerName), $form);
        }
    }

    /** Delete a reference
     * @access public
     * @return void
     */
    public function deleteAction()
    {
	$id = $this->getParam('id', 0);

        if (!(ctype_digit($id) && ($id > 0)))
	{
	    throw new Pas_Exception_Param($this->_missingParameter, 500);
        }

        if ($this->_request->isPost())
	{
            $postVariable = $this->_request->getPost('confirmDelete');
            $confirmDelete = isset($postVariable) ? strtoupper($postVariable) : "NO";

	    // if Yes, delete the record
            if ('YES' === $confirmDelete)
	    {
		$this->deleteReference((int)$this->_request->getPost('id'));
                $this->getFlash()->addMessage('Reference deleted!');
            }
            else
	    {
                $this->getFlash()->addMessage('Reference NOT deleted!');
            }

	    $this->redirectToRecord($this->_request->getPost());
        }
	else
	{
            $this->view->id = $id;
            $this->view->bib = $this->getBibliography()->fetchFindBook($id, $this->getController());
        }
    }

    /** Add a reference to the table
     * @access private
     */
    private function addReference($formData)
    {
         $formData['findID'] = $this->getParam('secID');
         unset($formData['authors']);
         $this->getBibliography()->add($formData);
    }

    /** Delete the reference from Bibliography
     * @access private
     */
    private function deleteReference($id)
    {
	$where = array();
	$where[] = $this->getBibliography()->getAdapter()->quoteInto('id = ?', $id);
        $this->getBibliography()->delete($where);
    }

    private function redirectToRecord($data)
    {
        $findID = $data['findID'];
        $this->setController($data['controller']);
        $this->setRedirect($this->getController());
        $this->redirect($this->getRedirect() . 'record/id/' . $findID . self::REFERENCE);
    }

    /** Populate the form
     * @access private
     */
    private function populateForm($pubs, $bibliography, $form)
    {
	if (count($bibliography) > 0)
	{
	    $form->populate($bibliography['0']);
            $titles = $pubs->getTitlesPairs($bibliography[0]['authors']);
            $form->pubID->addMultiOptions($titles);
            $form->pubID->setValue($bibliography[0]['pubID']);
	}
    }

    private function checkIfReferenceExists($getParamCopy, $form)
    {
	$reference = $this->getBibliography()->getLastReference($this->_helper->identity->getPerson()->id);
	$this->view->reference = $reference;

        if ('lastReference' === $getParamCopy)
	{
	    $this->_helper->copyLastReference($form, (!empty($reference)) ? $reference[0] : null);
        }
    }
}
