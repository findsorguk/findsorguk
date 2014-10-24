<?php
/** Controller for displaying Roman reece periods
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Reeces
 * @uses Pas_Solr_FieldGeneratorFinds
 * @uses Pas_Solr_Handler
 * @uses Emperors
 * @uses RevTypes
 */
class RomanCoins_ReeceperiodsController extends Pas_Controller_Action_Admin {
	
    /** The Reece period model
     * @access protected
     * @var \Reeces
     */
    protected $_reeces;
	   
    /** Set up the ACL and contexts
     * @access public 
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow(null);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('period',$contexts)
		->initContext();
	$this->_reeces = new Reeces();
        
    }
    
    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
	$select = array( 'query' => 'reeceID:[* TO *]' );
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler();
        $search->setCore('objects');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$params['format'] = $context;
	$search->setFields($fields->getFields());
	$search->setFacets(array('reeceID'));
	$search->setParams($params);
	$search->execute();
        $statistics = $search->processFacets();
        $stats = array();
        foreach($statistics['reeceID'] as $k => $v){
            $stats[$k] = (string)$v;
        }
	ksort($stats);
	$cleaned = array();
	foreach($stats as $k => $v){
            $cleaned[] = array(
                'period_name' => 'Reece period ' . $k, 
    		'description' => $v, 
    		'id' => $k
                    );
        }
	$this->view->reeces = $cleaned;
    }
    
    /** Set up the individual period
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function periodAction() {
	if($this->_getParam('id',false)) {
            $id = (int)$this->_getParam('id');
            $this->view->periods = $this->_reeces->getReecePeriodDetail($id);
            $emperors = new Emperors();
            $this->view->reeces = $emperors->getReeceDetail($id);
            $reverses = new RevTypes();
            $this->view->reverses = $reverses->getRevTypeReece($id);    
	} else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
	}
    }
}