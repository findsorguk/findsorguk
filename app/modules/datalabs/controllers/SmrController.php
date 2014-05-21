<?php
/** Controller for displaying the SMRs provided by NMR EH
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_SmrController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('hero',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('record',$this->_contexts)
		->addActionContext('index',$this->_contexts)
		->initContext();
    }
	const REDIRECT = 'database/smr/';
	/** Index page for smrs
	*/
	public function indexAction() {
	$form = new SolrForm();
    $form->removeElement('thumbnail');
    $form->q->setLabel('Search SMR list: ');
    $form->q->setAttribs(array('placeholder' => 'Try barrow for instance'));
    $this->view->form = $form;

    $params = $this->_getAllParams();

    $search = new Pas_Solr_Handler('beogeodata');
    $search->setFields(array('*')
    );
	$search->setFacets(array('county', 'district'));

    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
            && !is_null($this->_getParam('submit'))){

    if ($form->isValid($form->getValues())) {
    $params = $form->getValues();
	unset($params['csrf']);
    $this->_helper->Redirector->gotoSimple('index','smr','database',$params);
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
    $params['source'] = 'smrdata';
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

	/** Individual SMR record
	*/
	public function recordAction() {
	if($this->_getParam('id',false)) {
	$smrs = new ScheduledMonuments();
	$this->view->smrs = $smrs->getSmrDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** SMR by WOEID
	*/
	public function bywoeidAction() {
	if($this->_getParam('number',false)) {
	$this->view->woeid = $this->_getParam('number');
	$smrs = new ScheduledMonuments();
	$this->view->smrs = $smrs->getSmrsByWoeid($this->_getParam('number'),$this->_getParam('page'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	public function mapAction(){

	}
}