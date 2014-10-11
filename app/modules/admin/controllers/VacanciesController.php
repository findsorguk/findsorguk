<?php
/** Controller for administering vacancies 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Vacancies
 * @uses VacancyForm
 * @uses Pas_Exception_Param
*/
class Admin_VacanciesController extends Pas_Controller_Action_Admin {
	
    /** The vacancies model
     * @access protected
     * @var \Vacancies
     */
    protected $_vacancies;
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('flos',null);
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        $this->_vacancies = new Vacancies();
        
    }
    
    /** The redirect constant
     * 
     */
    const REDIRECT = '/admin/vacancies';
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
    */	
    public function indexAction() {
        $this->view->currentvacs = $this->_vacancies
                ->getJobsAdmin($this->_getParam('page'));
    }
    /** Add a new vacancy
     * @access public
     */	
    public function addAction() {
        $form = new VacancyForm();
        $form->submit->setLabel('Add a new vacancy');
        $this->view->form = $form;
        if($this->getRequest()->isPost() && 
                $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->_vacancies->add($form->getValues());
                $this->getFlash()->addMessage('Vacancy details created: ' 
                        . $form->getValue('title'));
                $this->redirect(self::REDIRECT);
            } else {
                $form->populate($form->getValues());
            }
        }
    }
    
    /** Edit a vacancy
     * @access public
     * @return void
     */		
    public function editAction() {
        if($this->_getParam('id',false)) {
            $form = new VacancyForm();
            $form->submit->setLabel('Update details');
            $this->view->form = $form;
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
                if ($form->isValid($form->getValues())) {
                    $where = array();
                    $where[] = $this->_vacancies->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
                    $this->_vacancies->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Vacancy details updated!');
                    $$this->redirect(elf::REDIRECT);
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_getParam('id', 0);
                if ($id > 0) {
                    $vac = $this->_vacancies->fetchRow('id = ' . $id);
                    if(count($vac)) {
                        $form->populate($vac->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound, 404);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParamter, 500);
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
                $where = 'id = ' . (int)$id;
                $this->_vacancies->delete($where);
                $this->getFlash()->addMessage('Record deleted');
            }
            $this->_redirect(self::REDIRECT);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->vac = $this->_vacancies->fetchRow('id = ' . $id);
            }
        }
    }
}