<?php
/**
 * A controller to analyse the sites technological interface via google analytics.
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 */
class Analytics_TechnologyController extends Pas_Controller_Action_Admin {
   
    /** Initialise the variables
     * @access public
     */
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
    }
    
    /** Get the base metrics
     * @access public
     */
    public function metricsAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_HOSTNAME  			
    		)
    		);
    	$analytics->setMax(10);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME);
    	
    	$this->view->results = $analytics->getData();
    	
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	new Pas_Analytics_Timespan(); $timeframe->setTimespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_DOMAIN_LOOKUP_TIME
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_HOSTNAME  			
    		)
    		);
    	$analytics->setMax(10);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_AVG_DOMAIN_LOOKUP_TIME);
    	$this->view->lookup = $analytics->getData();
    }
    
    /** Query browsers used
     * @access public
     */
    public function browsersAction() {   	
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
    		Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_BROWSER			
    		)
    		);
    	$analytics->setMax(100);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    /** Interrogate an individual browser type
     * @access public
     */
    public function browserAction()  {   	
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
    		Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_BROWSER_VERSION			
    		)
    		);
    	$analytics->setMax(100);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	if($this->_getParam('identifier',false)){
	    	$analytics->setFilters(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_BROWSER 
                        . Zend_Gdata_Analytics_DataQuery::EQUALS 
                        . $this->_getParam('identifier')
	    	));	  
	    	}
    	$this->view->results = $analytics->getData();
    }
    
    /** Discover which mobiles use the site
     * @access public
     */
    public function mobilesAction() {
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
                Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
                Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
                ));
        $analytics->setDimensions(array(Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING));
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSegment(11);  	
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }
    
    /** Learn more about a specific mobile device
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
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
                Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_MODEL,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_INFO
                )
                );
        if($this->_getParam('brand',false)){
        $analytics->setFilters(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING 
                . Zend_Gdata_Analytics_DataQuery::EQUALS 
                . $this->_getParam('brand')
        ));	  
        }
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSegment(11);  	
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }

    /** Discover more about tablet interaction
     * @access public
     */
    public function tabletsAction(){
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
                Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
                Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING
                )
                );
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSegment(13);  	
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }

    /** Discover more about desktop operating system interaction
    * @access public
    */
   public function desktoposAction() {
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
               Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
               Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
               )
               );
       $analytics->setDimensions(array(
               Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
               )
               );
       $analytics->setFilters(array(
               Zend_Gdata_Analytics_DataQuery::DIMENSION_IS_MOBILE . Zend_Gdata_Analytics_DataQuery::EQUALS_NOT . 'Yes'
       ));	  
       $analytics->setMax(500);
       $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
       $analytics->setSortDirection(true);
       $this->view->results = $analytics->getData();
    }

    /** Find out about the desktop OS version used
     * @access public
     */
    public function desktoposversionAction() {
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
              Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
              Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
              )
              );
        $analytics->setDimensions(array(
              Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM_VERSION,
              )
              );
        $analytics->setFilters(array(
              Zend_Gdata_Analytics_DataQuery::DIMENSION_IS_MOBILE 
              . Zend_Gdata_Analytics_DataQuery::EQUALS_NOT 
              . 'Yes',
              Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM	
              . Zend_Gdata_Analytics_DataQuery::REGULAR 
              . $this->_getParam('os')    	
              ));	  
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
        $this->view->os = $this->_getParam('os');
    }

    /** Find out about mobile OS interaction
     * @access public
     */
    public function mobileosAction() {
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
        Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
        Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
        )
        );
        $analytics->setDimensions(array(
        Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
        )
        );
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSegment(11);  	
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }

    /** Find mobile OS versions
     * @access public
     */
    public function mobileosversionAction(){
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
            Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
            Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
            )
            );
        $analytics->setDimensions(array(
            Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM_VERSION,
            )
            );
        $analytics->setFilters(array(
            Zend_Gdata_Analytics_DataQuery::DIMENSION_IS_MOBILE . 
            Zend_Gdata_Analytics_DataQuery::EQUALS 
                . 'Yes',
            Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM
                . Zend_Gdata_Analytics_DataQuery::REGULAR 
                . $this->_getParam('os')    	
            ));	
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSegment(11);  	
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
        $this->view->os = $this->_getParam('os');
    }

    /** Find out about OS and browser combos
     * @access public
     */
    public function ostobrowserAction(){
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
            Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE,
            Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES

            )
            );
        $analytics->setDimensions(array(
            Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
            Zend_Gdata_Analytics_DataQuery::DIMENSION_BROWSER
            )
            );
        $analytics->setFilters(array(
            Zend_Gdata_Analytics_DataQuery::DIMENSION_IS_MOBILE . Zend_Gdata_Analytics_DataQuery::EQUALS_NOT . 'Yes'
        ));	  
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }
}