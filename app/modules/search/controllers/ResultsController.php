<?php
/** Controller for the Staffordshire symposium
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/

class Search_ResultsController extends Pas_Controller_Action_Admin {

	protected $_solr;
	/**
	 * Set up the ACL
	 */
	public function init() {
	$this->_helper->_acl->allow('public',null);
	}


	public function indexAction(){
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler();
        $search->setCore('beocontent');
	$search->setFields(array('*'));
	$search->setFacets(array('section'));
	$search->setParams($params);
	$search->execute();
	$this->view->query = $this->_getParam('q');
	$this->view->facets = $search->processFacets();
	$this->view->paginator = $search->createPagination();
	$this->view->results = $search->processResults();
	}

}

