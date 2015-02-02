<?php

/** A controller for manipulating organisational data.
 *
 * Data for organisations that people belong to is stored in the database and
 * people can be attached to just one at present.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Organisations
 * @uses OrganisationFilterForm
 * @uses Pas_Exception_Param
 * @uses Pas_ArrayFunctions
 *
 */
class Database_OrganisationsController extends Pas_Controller_Action_Admin
{

    /** The organisations model
     * @access protected
     * @var \Organisations
     */
    protected $_organisations;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('flos', null);

    }


    public function getOrganisations()
    {
        $this->_organisations = new Organisations();
        return $this->_organisations;
    }

    /** The redirect uri
     *
     */
    const REDIRECT = 'database/organisations/';

    /** Index page, listing all organisations
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $paginator = $this->getOrganisations()->getOrganisations((array)$this->getAllParams());
        $this->view->paginator = $paginator;
        $form = new OrganisationFilterForm();
        $this->view->form = $form;
        $form->organisation->setValue($this->getParam('organisation'));
        $form->contact->setValue($this->getParam('contact'));
        $form->contactpersonID->setValue($this->getParam('contactpersonID'));
        $form->county->setValue($this->getParam('county'));
        if ($this->_request->isPost() && !is_null($this->getParam('submit'))) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $cleaner = new Pas_ArrayFunctions();
                $params = $cleaner->array_cleanup($formData);
                $where = array();
                foreach ($params as $key => $value) {
                    if (!is_null($value)) {
                        $where[] = $key . '/' . urlencode(strip_tags($value));
                    }
                }

                $whereString = implode('/', $where);
                $query = $whereString;
                $this->redirect(self::REDIRECT . 'index/' . $query . '/');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Details page for an organisation.
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function organisationAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->orgs = $this->getOrganisations()->getOrgDetails($this->getParam('id'));
            $this->view->members = $this->getOrganisations()->getMembers($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Edit an organisation's details
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new OrganisationForm();
        $form->submit->setLabel('Update organisation');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $updateData = $form->getValues();
                unset($updateData['contact']);
                $audit = $this->getOrganisations()->fetchRow('id=' . $this->getParam('id'));
                $oldArray = $audit->toArray();
                $where = array();
                $where[] = $this->getOrganisations()->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $this->getOrganisations()->update($updateData, $where);
                $this->_helper->audit(
                    $updateData,
                    $oldArray,
                    'OrganisationsAudit',
                    $this->getParam('id'),
                    $this->getParam('id')
                );
                $this->getFlash()->addMessage('Organisation information updated!');
                $this->redirect(self::REDIRECT . 'organisation/id/' . $this->getParam('id'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $organisation = $this->getOrganisations()->fetchRow('id=' . $id);
                $form->populate($organisation->toArray());
            }
        }
    }

    /** Add an organisation
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new OrganisationForm();
        $form->submit->setLabel('Add a new organisation');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['secuid'] = $this->secuid();
                unset($data['contact']);
                $insert = $this->getOrganisations()->add($data);
                $this->redirect(self::REDIRECT . 'organisation/id/' . $insert);
                $this->getFlash()->addMessage('Record created!');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Delete an organisation
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function deleteAction()
    {
        if ($this->getParam('id', false)) {
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = 'id = ' . $id;
                    $this->getOrganisations()->delete($where);
                    $this->getFlash()->addMessage('Record deleted!');
                }
                $this->redirect(self::REDIRECT);
            } else {
                $id = (int)$this->_request->getParam('id');
                if ($id > 0) {
                    $this->view->organisation = $this->getOrganisations()->fetchRow('id=' . $id);
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

}