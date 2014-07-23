<?php
/** Controller for adding static contents to the Scheme website
* 
* @category   Pas
* @package Pas_Controller_Action
* @subpackage Admin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Admin_ContentController extends Pas_Controller_Action_Admin {
	
    protected $_content;

    protected $_cache;
    /** Initialise the ACL and contexts
    */ 	
    public function init() {
    $this->_helper->_acl->allow('fa',null);
    $this->_helper->_acl->allow('admin',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_content = new Content();
//    $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
//	$this->_solr = new Solarium_Client($this->_solrConfig);
    }
    /** Display index page
    */ 
    public function indexAction() {
    	$form = new ContentSearchForm();
    	$form->submit->setLabel('Search content');
	    $this->view->form = $form;
        $params = $this->array_cleanup($this->_getAllParams());
        $search = new Pas_Solr_Handler();
        $search->setCore('beocontent');
        $search->setFields(array(
            'id', 'title', 'section', 
            'publishState', 'created', 'updated', 
            'type', 'createdBy', 'updatedBy'
            ));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){
        if ($form->isValid($form->getValues())) {
        $params = $this->array_cleanup($form->getValues());
        $this->_helper->Redirector->gotoSimple('index','content','admin',$params);
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
         $params['type'] = 'sitecontent';
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->contents = $search->processResults();

	}
    /** Add contents
    */ 	
    public function addAction() {
    $form = new ContentForm();
    $form->submit->setLabel('Add new content to system');
    $form->author->setValue($this->getIdentityForForms());
    $this->view->form = $form;
    if($this->getRequest()->isPost() 
            && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $insertData = $form->getValues();
    $content = new Content();
    $insert = $content->add($insertData);
    //Update the solr instance
    $this->_helper->solrUpdater->update('beocontent', $insert, 'content');
    //Add a flash message
    $this->_flashMessenger->addMessage('Static content added');
    //Redirect to index
    $this->_redirect('/admin/content');
    } else {
    $form->populate($form->getValues());
    }
    }
    }

    /** Edit a content article
    */ 		
    public function editAction() {
    if($this->_getParam('id',false)) {
    $form = new ContentForm();
    $form->submit->setLabel('Submit changes');
    $form->author->setValue($this->getIdentityForForms());
    $this->view->form = $form;
    if($this->getRequest()->isPost() 
            && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
    $where = array();
    $where[] = $this->_content->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $oldData = $this->_content->fetchRow($this->_content->select()->where('id= ?' , (int)$this->_getParam('id')))->toArray();
    $this->_helper->audit($updateData, $oldData, 'ContentAudit', 
            $this->_getParam('id'), $this->_getParam('id'));
	$this->_content->update($updateData, $where);
    $this->_helper->solrUpdater->update('beocontent', $this->_getParam('id'), 'content');  
    $this->_flashMessenger->addMessage('You updated: <em>' . $form->getValue('title') 
    . '</em> successfully. It is now available for use.');
 	$cache = Zend_Registry::get('cache');
 	$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
    $this->_redirect('admin/content/');
    } else {
    $form->populate($form->getValues());
    }
    } else {
    // find id is expected in $params['id']
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
    $content = $this->_content->fetchRow('id=' . (int)$id)->toArray();
    if($content) {
    $form->populate($content);
    } else {
            throw new Pas_Exception_Param($this->_nothingFound);
    }
    }
    }
    } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
    }
    }

    /** Delete article
    */ 		
    public function deleteAction() {
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $contents = new Content();
    $where = 'id = ' . $id;
    $contents->delete($where);
    $this->_flashMessenger->addMessage('Record deleted!');
    $this->_helper->solrUpdater->deleteById('beocontent', $id);
    }
    $this->_redirect('/admin/content/');
    }  else  {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $contents = new Content();
    $this->view->content = $contents->fetchRow('id=' . $id);
    }
    }
    }
	
    
private function array_cleanup( $array ) {
        $todelete = array('submit','action','controller','module','csrf');
	foreach( $array as $key => $value ) {
        foreach($todelete as $match){
    	if($key == $match){
    		unset($array[$key]);
    	}
        }
        }
        return $array;
        }
    
}
