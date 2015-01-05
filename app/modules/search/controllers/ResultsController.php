<?php
/** Controller for the Staffordshire symposium
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Pas_Solr_Handler
 * 
 */

class Search_ResultsController extends Pas_Controller_Action_Admin {

    /**  Set up the ACL
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
        
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction(){
        $search = new Pas_Solr_Handler();
        $search->setCore('content');
        $search->setFields(array('*'));
        $search->setFacets(array('section'));
        $search->setParams($this->getAllParams());
        $search->execute();
        $this->view->query = $this->getParam('q');
        $this->view->facets = $search->processFacets();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
    }
}