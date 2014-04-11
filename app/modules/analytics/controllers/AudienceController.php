<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AudienceController
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Analytics_AudienceController 
    extends Pas_Controller_Action_Admin {
   
    const MAX_RESULTS = 100;
    	
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
		$this->_pword = $this->_helper->config()->webservice->google->password;
//		$this->getForm();
    }
    
     /** Retrieve the page number
        *
        */
	public function getPage()
	{
        $page = $this->_getParam('page');
		if(!isset($page)){
			$start = 1;
		} else {
			$start = $page ;
		}
		return $start;
	}
	
    public function getStart()
	{
		$p = $this->getPage();
		if(is_null($p) || $p == 1){
			$start = 1;
		} else {
			$start = (self::MAX_RESULTS) * ($p - 1) + 1;
		}
		return $start;
	}
    
    public function indexAction()
    {
    	$this->_helper->redirector('overview');
    }
    
    public function getForm()
    {
    $form = new AnalyticsFilterForm();
    $this->view->form = $form;	
    }
    
    public function overviewAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
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
    
    public function mapAction(){
    	
    }
    
    public function continentAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
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
    
    public function subcontinentAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
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
    
    public function cityAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
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
    
    
    public function countryAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
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
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_COUNTRY    			
    		)
    		);
    	$analytics->setMax(120);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    public function mobileAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
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
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_INFO,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_MODEL,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_INPUT_SELECTOR   			
    		)
    		);
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    	$this->view->results = $analytics->getData();
    }
    
    
    public function behaviourAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
			Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_VISITOR_TYPE   			
    		)
    		);
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->view->results = $analytics->getData();
    }
    
    public function hourlyAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_HOUR    			
    		)
    		);
    	$analytics->setMax(24);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::DIMENSION_HOUR);
    	$this->view->results = $analytics->getData();
    }
    
    public function languagesAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_LANGUAGE    			
    		)
    		);
    	$analytics->setMax(150);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(false);
    	$this->view->results = $analytics->getData();
    }
    
}