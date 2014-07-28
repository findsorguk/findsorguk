<?php
/** Controller for displaying individual's finds on the database.
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses SolrForm
 * @uses Pas_Solr_Handler
 * @uses Pas_ArrayFunctions
 * 
 */
class Database_MyschemeController extends Pas_Controller_Action_Admin {

    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('member',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $contexts = array('xml','json');
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('myimages', $contexts)
                ->addActionContext('myfinds', $contexts)
                ->addActionContext('recordedbyflos', $contexts)
                ->addActionContext('myinstitution', $contexts)
                ->initContext();
    }
    
    /** The array cleaning functions
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_cleaner;
    
    /** The solr object
     * @access protected
     * @var \Pas_Solr_Handler
     */
    protected $_solr;
    
    /** Get the solr object
     * @access public
     * @return \Pas_Solr_Handler
     */
    public function getSolr() {
        $this->_solr = new Pas_Solr_Handler();
        return $this->_solr;
    }

    /** The array cleaner
     * @access public
     * @return \Pas_ArrayFunctions
     */
    public function getCleaner() {
        $this->_cleaner = new Pas_ArrayFunctions();
        return $this->_cleaner;
    }

    /** the redirect string
     * 
     */
    const REDIRECT = '/database/myscheme/';

    /** Redirect as no root access allowed
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->_flashMessenger->addMessage('No access to index page');
        $this->_redirect('/database/');
    }

    /** List of user's finds that they have entered.
     * @access public
     * @return void
     */
    public function myfindsAction() {
        $form = new SolrForm();
	$form->q->setLabel('Search the database: ');
        $this->view->form = $form;
        $params = $this->_getAllParams();
        $search = $this->getSolr();
        $search->setCore('beowulf');
        $search->setFields(array(
            'id', 'identifier', 'objecttype',
            'title', 'broadperiod','imagedir',
            'filename','thumbnail','old_findID',
            'description', 'county', 'workflow',
            'knownas', 'fourFigure','updated',
            'created'
            ));
        $search->setFacets(array('objectType','county','broadperiod','institution'));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){
            $params = $this->getCleaner()->array_cleanup($form->getValues());

            $this->_helper->Redirector->gotoSimple(
                    'myfinds','myscheme','database',
                    $params);
        } else {
            $form->populate($this->_getAllParams());
        }
        if(!isset($params['q']) || $params['q'] == ''){
            $params['q'] = '*';
        }
        $params['createdBy'] =  $this->getIdentityForForms();
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
        $this->view->facets = $search->processFacets();
        $this->view->stats = $search->processStats();
    }

    /** Recorded by flo finds list action
     * @access public
     * @return void
     */
    public function recordedbyflosAction(){
        if(!is_null($this->getAccount()->peopleID)){
            $form = new SolrForm();
            $form->q->setLabel('Search the database: ');
            $this->view->form = $form;
            $params = $this->_getAllParams();
            $params['finderID'] = $this->getAccount()->peopleID;
            $params['-createdBy'] = $this->getAccount()->id;
            $search = $this->getSolr();
            $search->setCore('beowulf');
            $search->setFields(array(
                'id', 'identifier', 'objecttype',
                'title', 'broadperiod','imagedir',
                'filename','thumbnail','old_findID',
                'description', 'county', 'workflow',
                'knownas', 'fourFigure','updated',
                'created')
            );
            $search->setFacets(array('objectType','county','broadperiod','institution'));
            if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){
            $params = $this->getCleaner()->array_cleanup($form->getValues());

            $this->_helper->Redirector->gotoSimple(
                    'recordedbyflos','myscheme','database',
                    $params);
            } else {
                $form->populate($this->_getAllParams());
            }
            if(!isset($params['q']) || $params['q'] == ''){
                $params['q'] = '*';
            }
            $search->setParams($params);
            $search->execute();
            $this->view->paginator = $search->createPagination();
            $this->view->finds = $search->processResults();
            $this->view->facets = $search->processFacets();
            $this->view->stats = $search->processStats();
        } else {
            $this->_redirect('/error/accountproblem');
        }
    }

    /** Map action
     * @access public
     * @return void
     */
    public function mapAction(){
        $this->view->id = $this->getIdentityForForms();
    }

    /** the institutional map action    
     * @access public
     * @return void
     */
    public function institutionmapAction(){
        $this->view->inst = $this->getInstitution();
    }
    
    /** Finds recorded by an institution assigned to the user
     * @access public
     * @return void
     */
    public function myinstitutionAction() {
        $form = new SolrForm();
        $form->q->setLabel('Search the database: ');
        $this->view->form = $form;
        $params = $this->_getAllParams();
        $search = $this->getSolr();
        $search->setCore('beowulf');
        $search->setFields(array(
            'id', 'identifier', 'objecttype',
            'title', 'broadperiod','imagedir',
            'filename','thumbnail','old_findID',
            'description', 'county', 'workflow',
            'fourFigure', 'knownas', 'updated',
            'created'
            ));
        $search->setFacets(array(
            'objectType', 'county', 'broadperiod',
            'institution', 'workflow'
            ));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                    && !is_null($this->_getParam('submit'))){

            if ($form->isValid($form->getValues())) {
                $params = $this->getCleaner()->array_cleanup($form->getValues());

                $this->_helper->Redirector->gotoSimple(
                        'myinstitution', 'myscheme', 'database',
                        $params
                        );
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
        $params['institution'] =  $this->getInstitution();
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
        $this->view->facets = $search->processFacets();
        $this->view->stats = $search->processStats();
    }
    /** Display all images that a user has added.
     * @access public
     * @return void
     */
    public function myimagesAction() {
        $form = new SolrForm();
        $form->removeElement('thumbnail');
        $this->view->form = $form;
        $params = $this->_getAllParams();
        $search = $this->getSolr();
        $search->setCore('beoimages');
        $search->setFields(array(
            'id', 'identifier', 'objecttype',
            'title', 'broadperiod', 'imagedir',
            'filename', 'thumbnail', 'old_findID',
            'county','licenseAcronym','findID',
            'objecttype','institution','updated',
            'created'
            ));
        $search->setFacets(array('broadperiod','county', 'objecttype','institution'));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                    && !is_null($this->_getParam('submit'))){
            if ($form->isValid($form->getValues())) {
                $params = $this->getCleaner()->array_cleanup($form->getValues());
                $this->_helper->Redirector->gotoSimple(
                        'myimages', 'myscheme', 'database',
                        $params
                        );
            } else {
                $form->populate($form->getValues());
                $params = $form->getValues();
            }
        } else {
            $params = $this->_getAllParams();
            $form->populate($this->_getAllParams());
        }
        $params['show'] = 18;
        if(!isset($params['q']) || $params['q'] == ''){
            $params['q'] = '*';
        }
        $params['createdBy'] = $this->getIdentityForForms();
        $search->setParams($params);
        $search->execute();
        $search->processFacets();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
        $this->view->facets = $search->processFacets();
    }

    /** Get all treasure cases for a user
     * @access public
     * @return void
     */
    public function mytreasurecasesAction(){
        $form = new SolrForm();
        $this->view->form = $form;
        $params = $this->_getAllParams();
        $search = $this->getSolr();
        $search->setCore('beowulf');
        $search->setFields(array(
            'id', 'identifier', 'objecttype',
            'title', 'broadperiod','imagedir',
            'filename','thumbnail','old_findID',
            'description', 'county', 'workflow',
            'updated', 'created'
            )
        );
        $search->setFacets(array(
            'objectType', 'county', 'broadperiod', 
            'discovered', 'institution','workflow'
            ));
        if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                    && !is_null($this->_getParam('submit'))){

            if ($form->isValid($form->getValues())) {
                $params = $this->getCleaner()->array_cleanup($form->getValues());
                $this->_helper->Redirector->gotoSimple(
                        'mytreasurecases', 'myscheme', 'database', 
                        $params
                        );
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
        $params['finderID'] =  $this->getAccount()->peopleID;
        $params['treasure'] = 1;
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
        $this->view->facets = $search->processFacets();
    }
}