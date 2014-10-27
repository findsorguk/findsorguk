<?php
/** Controller for displaying publications information
 *
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses SolrForm
 * @uses Publications
 * @uses Pas_Solr_Handler
 * @uses Pas_ArrayFunctions
 * @uses Pas_Exception_Param
 * @uses PublicationForm
 *
*/
class Database_PublicationsController extends Pas_Controller_Action_Admin {

    protected $_publications;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',array('index','publication'));
        $this->_helper->_acl->deny('public',array('add','edit','delete'));
        $this->_helper->_acl->allow('flos',null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('publication', array('xml','json'))
                ->addActionContext('index', array('xml','json'))
                ->initContext();
        
        $this->_publications = new Publications();
    }

    /** The redirect uri
     *
     */
    const REDIRECT = 'database/publications/';

    /** Display of publications with filtration
     * @access public
     * @return void
     */
    public function indexAction() {
        $form = new SolrForm();
        $form->removeElement('thumbnail');
        $form->q->setLabel('Search the publications: ');
        $form->q->setAttrib('placeholder', 'Try Geake for example');
        $this->view->form = $form;
        $cleaner = new Pas_ArrayFunctions();
        $params = $cleaner->array_cleanup($this->_getAllParams());
        $search = new Pas_Solr_Handler();
        $search->setCore('publications');
        $search->setFields(array('*'));
        $search->setFacets(array('publisher','yearPublished'));

        if($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){

            if ($form->isValid($form->getValues())) {
                $this->_helper->Redirector->gotoSimple(
                        'index','publications','database',$params);
            } else {
                $form->populate($form->getValues());
                $params = $form->getValues();
            }
        } else {
            $params = $this->_getAllParams();
            $form->populate($this->_getAllParams());
        }

        if(!isset($params['q']) || $params['q'] == ''){
            $params['q'] = '*';
        }
        $params['sort'] = 'title';
        $params['direction'] = 'asc';
        $search->setParams($params);
        $search->execute();
        $this->view->facets = $search->processFacets();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
    }

    /** Display details of publication
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function publicationAction() {
        if($this->_getParam('id',false)) {
            $this->view->publications = $this->_publications
                    ->getPublicationDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** Add a publication
     * @access public
     * @return void
     */
    public function addAction() {
        $form = new PublicationForm();
        $form->submit->setLabel('Submit new');
        $this->view->form = $form;
        if($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())){
            $insertData = $form->getValues();
            $secuid = $this->_helper->GenerateSecuID();
            $insertData['secuid'] = $secuid;
            $insert = $this->_publications->add($insertData);
            $this->_helper->solrUpdater->update('publications', $insert);
            $this->redirect(self::REDIRECT . 'publication/id/' . $insert);
            $this->getFlash()->addMessage('A new reference work has been '
                    . 'created on the system!');
        } else {
            $form->populate($form->getValues());
        }
    }

    /** Edit publication details
     * @access public
     * @return void
     */
    public function editAction() {
        $form = new PublicationForm();
        $form->submit->setLabel('Update publication');
        $this->view->form = $form;
        if($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $updateData = $form->getValues();
                $where = array();
                $where =  $this->_publications->getAdapter()
                        ->quoteInto('id = ?', $this->_getParam('id'));
                $update = $this->_publications->update($updateData,$where);
                $this->_helper->solrUpdater->update('publications',
                        $this->_getParam('id'));
                $this->getFlash()->addMessage('Details for "'
                        . $form->getValue('title') . '" updated!');
                $$this->redirect(elf::REDIRECT . 'publication/id/'
                        . $this->_getParam('id'));
            } else {
                $form->populate($form->getValues());
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $publication = $this->_publications->fetchRow('id='.$id);
                $form->populate($publication->toArray());
            }
        }
    }
    /** Delete publication details
     * @access public
     * @throws Pas_Exception_Param
     * @return void
     */
    public function deleteAction() {
        if($this->_getParam('id',false)) {
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = array();
                    $where[] =  $this->_publications->getAdapter()
                            ->quoteInto('id = ?', $this->_getParam('id'));
                    $this->getFlash()->addMessage('Record deleted!');
                    $this->_publications->delete($where);
                    $this->_helper->solrUpdater->deleteById('publications', $id);
                }
                $this->redirect(self::REDIRECT);
            } else {
                $id = (int)$this->_request->getParam('id');
                if ($id > 0) {
                    $this->view->publication = $this->_publications
                            ->fetchRow('id= ' . (int) $this->_getParam('id'));
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
