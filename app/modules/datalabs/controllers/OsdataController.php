<?php
/** Controller for displaying os opendata gazetteer
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_OsdataController extends Pas_Controller_Action_Admin {

	protected $_contexts;

	/** Set up the ACL and contexts
	*/
	public function init(){
	$this->_helper->_acl->allow('public',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('oneto50k',$this->_contexts)
		->addActionContext('index',$this->_contexts)
		->initContext();
	}

	const REDIRECT = 'database/osdata/';

	/** Display a paginated list of OS data points
	*/
	public function indexAction() {
	$form = new SolrForm();
    $form->removeElement('thumbnail');
        $form->q->setLabel('Search OS open data: ');
    $form->q->setAttribs(array('placeholder' => 'Try barrow for instance'));
    $this->view->form = $form;

    $params = $this->_getAllParams();

    $search = new Pas_Solr_Handler('beogeodata');
    $search->setFields(array('*')
    );
	$search->setFacets(array('county'));

    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
            && !is_null($this->_getParam('submit'))){

    if ($form->isValid($form->getValues())) {
    $params = $form->getValues();
	unset($params['csrf']);
    $this->_helper->Redirector->gotoSimple('index','osdata','database',$params);
    } else {
    $form->populate($form->getValues());
    $params = $form->getValues();
    }
    } else {

    $params = $this->_getAllParams();
    $form->populate($this->_getAllParams());


    }

	$q = $this->_getParam('q');
	if(is_null($q)){
	$params['q'] = 'type:R OR type:A';
	} else {
		$params['q'] = 'type:R || type:A && ' . $q;
	}
	$params['source'] = 'osdata';
    $params['sort'] = 'id';
    $params['direction'] = 'asc';
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();
	$this->view->facets = $search->_processFacets();
    }

	/** Set up the one to 50k entry page
	*/
	public function oneto50kAction(){
	if($this->_getParam('id',false)){
	$gazetteers = new Osdata();
	$this->view->gazetteer = $gazetteers->getGazetteer($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	public function mapAction() {
	}
}

