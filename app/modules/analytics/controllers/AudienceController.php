<?php
/**
 * Description of AudienceController
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 * 
 */
class Analytics_AudienceController extends Pas_Controller_Action_Admin {
   
    /** The maximum results to return
     * 
     */ 
    const MAX_RESULTS = 100;
    	
    /** Initialise the variables
     * @access public
     */
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
    }
    
    /** Get the start number
     * @access public
     * @return integer
     */
    public function getStart() {
        $p = $this->getPage();
        if(is_null($p) || $p == 1){
            $start = 1;
        } else {
            $start = (self::MAX_RESULTS) * ($p - 1) + 1;
        }
        return $start;
    }
    
    /** The index action
     * @access public
     * @return void
     */
    public function indexAction(){
    	$this->_helper->redirector('overview');
    }
    
    /** Get the analytics form for filtering
     * @access public
     */
    public function getForm(){
        $form = new AnalyticsFilterForm();
        $this->view->form = $form;	
    }
    
    /** The over view page
     * 
     */
    public function overviewAction(){
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
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_DATE    			
    		)
    		);
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::DIMENSION_DATE);
    	$this->view->results = $analytics->getData();
    }
    
   /** The map action
    * @access public
    */
    public function mapAction(){
        //Magic in view
    }
    
    /** Get data by continent
     * @access public
     */
    public function continentAction() {
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
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_CONTINENT    			
    		)
    		);
    	$analytics->setMax(120);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    /** Get data by subcontinent
     * @access public
     */
    public function subcontinentAction() {
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
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SUB_CONTINENT    			
    		)
    		);
    	$analytics->setMax(120);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    /** Get data by a city
     * @access public
     */
    public function cityAction() {
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
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_CITY    			
    		)
    		);
    	$analytics->setMax(100);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$analytics->setStartIndex($this->getStart());
    	$this->view->results = $analytics->getData();
		$paginator = Zend_Paginator::factory((int)$analytics->getTotal());
        $paginator->setCurrentPageNumber((int)$this->getPage())
			->setItemCountPerPage((int)self::MAX_RESULTS);
		$this->view->paginator = $paginator;
    }
    
    
    /** Get data by a country
     * @access public
     */
    public function countryAction() {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
                    Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
                ));
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_COUNTRY    			
    		));
    	$analytics->setMax(120);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    /** Get audience data by mobile usage
     * @access public
     */
    public function mobileAction() {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
                    Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
                ));
    	$analytics->setDimensions(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING,
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_INFO,
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_MODEL,
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_INPUT_SELECTOR   			
    		));
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    	$this->view->results = $analytics->getData();
    }
    
    /** Get data by behavioural action
     * @access public
     */
    public function behaviourAction() {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
    		));
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_VISITOR_TYPE   			
    		)
    		);
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    /** Get data by hourly interaction
     * @access public
     */
    public function hourlyAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		));
    	$analytics->setDimensions(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_HOUR    			
    		));
    	$analytics->setMax(24);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::DIMENSION_HOUR);
    	$this->view->results = $analytics->getData();
    }
    
    /** Get data by languages used
     * @access public
     */
    public function languagesAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		));
    	$analytics->setDimensions(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_LANGUAGE    			
                ));
    	$analytics->setMax(150);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(false);
    	$this->view->results = $analytics->getData();
    }
}