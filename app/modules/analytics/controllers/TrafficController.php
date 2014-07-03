<?php
/** A controller to doscover more from the Google Analytics api about traffic 
 * to the site.
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 */
class Analytics_TrafficController extends Pas_Controller_Action_Admin {

    /** Initialise the variables
     * @access public
     */
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
    }
    
    /** The index action
     * @access public 
     */
    public function indexAction() {
        //Redirect to the overview page
    	$this->_helper->redirector('overview');
    }
    
    /** Discover an overview
     * @access public
     */
    public function overviewAction() {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MEDIUM   			
    		)
    		);
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();	
    }
    
    public function viareferralAction() {
    }
    
    public function viasearchAction(){
    }
}


