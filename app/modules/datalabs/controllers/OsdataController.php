<?php

/** Controller for displaying os opendata gazetteer
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Datalabs_OsdataController extends Pas_Controller_Action_Admin
{

    protected $_contexts;

    /** Set up the ACL and contexts
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);

        $this->_contexts = array('xml', 'json');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addActionContext('oneto50k', $this->_contexts)
            ->addActionContext('index', $this->_contexts)
            ->initContext();
    }

    const REDIRECT = 'datalabs/osdata/';

    /** Display a paginated list of OS data points
     */
    public function indexAction()
    {
        $form = new SolrForm();
        $form->removeElement('thumbnail');
        $form->q->setLabel('Search OS open data: ');
        $form->q->setAttribs(array('placeholder' => 'Try barrow for instance'));
        $this->view->form = $form;

        $params = $this->getAllParams();

        $search = new Pas_Solr_Handler();
        $search->setCore('geodata');
        $search->setFields(array('*'));
        $search->setFacets(array('county'));

        if ($this->getRequest()->isPost() && !is_null($this->getParam('submit'))) {
            if ($form->isValid($this->_request->getPost())) {
                $params = $form->getValues();
                unset($params['csrf']);
                $this->_helper->Redirector->gotoSimple('index', 'osdata', 'datalabs', $params);
            } else {
                $form->populate($form->getValues());
                $params = $form->getValues();
            }
        } else {
            $form->populate($this->_request->getPost());
        }

        $q = $this->getParam('q');
        if (is_null($q)) {
            $params['q'] = 'type:R OR type:A';
        } else {
            $params['q'] = 'type:R || type:A && ' . $q;
        }
        $params['source'] = 'osdata';
        $params['sort'] = 'id';
        $params['direction'] = 'asc';
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
        $this->view->facets = $search->processFacets();
    }

    /** Set up the one to 50k entry page
     * @access public
     * @throws Pas_Exception_Param
     * @return void
     */
    public function oneto50kAction()
    {
        if ($this->getParam('id', false)) {
            $gazetteers = new OsData();
            $this->view->gazetteer = $gazetteers->getGazetteer($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** The map of OS data
     * @access public
     * @return void
     */
    public function mapAction()
    {
        // Magic in view
    }
}

