<?php
/** Controller for displaying publications information
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_PublicationsController extends Pas_Controller_Action_Admin {

	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',array('index','publication'));
	$this->_helper->_acl->deny('public',array('add','edit','delete'));
	$this->_helper->_acl->allow('flos',NULL);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('publication', array('xml','json'))
		->addActionContext('index', array('xml','json'))
		->initContext();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

	const REDIRECT = 'database/publications/';

	/** Display of publications with filtration
	*/
		public function indexAction() {
		$form = new SolrForm();
        $form->removeElement('thumbnail');
        $form->q->setLabel('Search the publications: ');
        $form->q->setAttrib('placeholder', 'Try Geake for example');
        $this->view->form = $form;

        $params = $this->array_cleanup($this->_getAllParams());
        $search = new Pas_Solr_Handler('beopublications');
        $search->setFields(array(
    	'*')
        );
        $search->setFacets(array('publisher','yearPublished'));

        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){

        if ($form->isValid($form->getValues())) {
        $params = $this->array_cleanup($form->getValues());

        $this->_helper->Redirector->gotoSimple('index','publications','database',$params);
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
        $this->view->facets = $search->_processFacets();
        $this->view->paginator = $search->_createPagination();
        $this->view->results = $search->_processResults();

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
	/** Display details of publication
	*/
	public function publicationAction() {
	if($this->_getParam('id',false)) {
	$publications = new Publications();
	$this->view->publications = $publications->getPublicationDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Add a publication
	*/
	public function addAction() {
	$form = new PublicationForm();
	$form->submit->setLabel('Submit new');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$insertData = $form->getValues();
	$secuid = $this->_helper->GenerateSecuID();
	$insertData['secuid'] = $secuid;
	$publications = new Publications();
	$insert = $publications->add($insertData);
	$this->_helper->solrUpdater->update('beopublications', $insert);
	$this->_redirect(self::REDIRECT . 'publication/id/' . $insert);
	$this->_flashMessenger->addMessage('A new reference work has been created on the system!');
	} else {
	$form->populate($form->getValues());
	}
	}
	}

	/** Edit publication details
	*/
	public function editAction() {
	$form = new PublicationForm();
	$form->submit->setLabel('Update publication');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
	$where = array();
	$publications = new Publications();
	$where =  $publications->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $publications->update($updateData,$where);
	$this->_helper->solrUpdater->update('beopublications', $this->_getParam('id'));
	$this->_flashMessenger->addMessage('Details for "' . $form->getValue('title') . '" updated!');
	$this->_redirect(self::REDIRECT . 'publication/id/' . $this->_getParam('id'));
	} else {
	$form->populate($form->getValues());
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$publications = new Publications();
	$publication = $publications->fetchRow('id='.$id);
	$form->populate($publication->toArray());
	}
	}
	}
	/** Delete publication details
	*/
	public function deleteAction() {
	if($this->_getParam('id',false)) {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$publications = new Publications();
	$where = array();
	$where =  $publications->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_flashMessenger->addMessage('Record deleted!');
	$publications->delete($where);
	$this->_helper->solrUpdater->deleteById('beopublications', $id);
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$publications = new Publications();
	$this->view->publication = $publications->fetchRow('id= ' . (int) $this->_getParam('id'));
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
