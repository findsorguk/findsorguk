<?php
/** Controller for managing latest news on the website
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses News
 * @uses NewsStoryForm
 * @uses Pas_ArrayFunctions
 * @uses Pas_Solr_Handler
 * 
 */
class Admin_NewsController extends Pas_Controller_Action_Admin {

    /** The news Model
     * @access protected
     * @var \News
     */
    protected $_news;
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('flos',null);
        $this->_news = new News();
        
    }

    /** The redirect uri
     * @access public
     * @return void
     */
    const REDIRECT = '/admin/news/';

    /** Display an index of news stories
     * @access public
     * @return void
     */
    public function indexAction(){
        $form = new ContentSearchForm();
        $form->submit->setLabel('Search content');
        $this->view->form = $form;
        $cleaner = new Pas_ArrayFunctions();
        $params = $cleaner->array_cleanup($this->_getAllParams());
        $search = new Pas_Solr_Handler();
        $search->setCore('beocontent');
        $search->setFields(array(
            'updated', 'updatedBy', 'publishState',
            'title', 'created', 'createdBy'
            ));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){

            if ($form->isValid($form->getValues())) {
                $params = $cleaner->array_cleanup($form->getValues());
                $this->_helper->Redirector->gotoSimple('index','news','admin',$params);
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
        $params['type'] = 'news';
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->news = $search->processResults();
    }
    
    /** Add and geocode a news story
     * @access public
     * @return void
     */
    public function addAction(){
        $form = new NewsStoryForm();
        $form->submit->setLabel('Add story');
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
        if ($form->isValid($form->getValues())) {
            $insert = $this->_news->addNews($form->getValues());
            $this->_helper->solrUpdater->update('content', $insert, 'news');
            $this->getFlash()->addMessage('News story created!');
            $this->redirect(self::REDIRECT);
        } else {
            $form->populate($this->_request->getPost());
        }
        }
    }
    /** Edit a news story
     * @access public
     * @return void
     */
    public function editAction(){
        $form = new NewsStoryForm();
        $form->submit->setLabel('Update story');
        $this->view->form = $form;
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
            if ($form->isValid($form->getValues())) {
                $this->_news->updateNews($form->getValues(), $this->_getParam('id'));
                $this->_helper->solrUpdater->update('content', $this->_getParam('id'), 'news');
                $this->getFlash()->addMessage('News story information updated!');
                $$this->redirect(elf::REDIRECT);
            } else {
                $form->populate($form->getValues());
            }
        } else {
            // find id is expected in $params['id']
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $form->populate($this->_news->fetchRow('id=' . $id)->toArray());
            }
        }
    }
    
    /** Delete a news story
     * @access public
     * @return void
     */
    public function deleteAction(){
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->_news->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
            }
            $this->_redirect(self::REDIRECT);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->new = $this->_news->fetchRow('id='.$id);
            }
        }
    }
}