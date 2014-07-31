<?php
/** Controller for user of specific level to add vacancy details
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @uses Vacancies
 * @uses VacancyForm
 * @use Pas_Exception_Param
 */
class Users_VacanciesController extends Pas_Controller_Action_Admin {

    /** The vacancies model
     * @access protected
     * @var \Vacancies
     *
     */
    protected $_vacancies;

    /** Setup the ACL
     * @access public
     * @return void
     */
    public function init() {
        $flosActions = array();
        $this->_helper->_acl->allow('flos',$flosActions);
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        $this->_vacancies = new Vacancies();
        parent::init();
    }
    /** Display list of current vacancies
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->currentvacs = $this->_vacancies->getJobsAdmin($this->_getParam('page'));
    }

    /** Add a vacancy
     * @access public
     * @return void
     */
    public function addAction() {
        $form = new VacancyForm();
        $form->submit->setLabel('Add a new job');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $insertdata = array(
                    'title' => $form->getValue('title'),
                    'salary' => $form->getValue('salary'),
                    'specification' => $form->getValue('specification'),
                    'regionID' => $form->getValue('regionID'),
                    'status' => $form->getValue('status'),
                    'live' => $form->getValue('live'),
                    'expire' => $form->getValue('expire'),
                    'created' => $this->getTimeForForms(),
                    'createdBy' => $this->getIdentityForForms()
                        );
                        $this->_vacancies->insert($insertdata);
                $this->_flashMessenger->addMessage('Vacancy details created: ' .$form->getValue('title'));
                $this->_redirect('/users/vacancies');
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit a vacancy
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction() {
        if($this->_getParam('id',false)) {
            $form = new VacancyForm();
            $form->submit->setLabel('Submit changes');
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $where = array();
                    $where[] = $this->_vacancies->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $insertdata = array(
                        'title' => $form->getValue('title'),
                        'salary' => $form->getValue('salary'),
                        'specification' => $form->getValue('specification'),
                        'regionID' => $form->getValue('regionID'),
                        'status' => $form->getValue('status'),
                        'live' => $form->getValue('live'),
                        'expire' => $form->getValue('expire'),
                        'updated' => $this->getTimeForForms(),
                        'updatedBy' => $this->getIdentityForForms()
                            );
                    $this->_vacancies->update($insertdata,$where);
                    $this->_flashMessenger->addMessage('Vacancy details updated!');
                    $this->_redirect('/users/vacancies');
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_getParam('id', 0);
                if ($id > 0) {
                    $vac = $this->_vacancies->fetchRow('id = '.$id);
                    if(count($vac)) {
                        $form->populate($vac->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParamter);
        }
    }
    /** Delete a vacancy
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost()) {
            $id = (int)$this->_getParam('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = '.(int)$id;
                $this->_vacancies->delete($where);
                $this->_flashMessenger->addMessage('Vacancy\'s information deleted! This cannot be undone.');
            }
            $this->_redirect('/users/vacancies/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
            $this->view->vac = $this->_vacancies->fetchRow('id = '.$id);
            }
        }
    }
}