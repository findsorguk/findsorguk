<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContentController
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Analytics_SocialController 
    extends Pas_Controller_Action_Admin {
    	
    	const MAX_RESULTS = 20;
    	
    	
    	public function init()
    	{
			$this->_helper->Acl->allow(null);
        	$this->_ID = $this->_helper->config()->webservice->google->username;
			$this->_pword = $this->_helper->config()->webservice->google->password;
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
				$start = $page;
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
	
    	public function interactionsAction()
    	{
			$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
	    	$analytics->setProfile(25726058);
	    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
	    	$dates = $timeframe->getDates();
	    	$analytics->setStart($dates['start']);
	    	$analytics->setEnd($dates['end']);
	    	$analytics->setMetrics(array(
	    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
	    		)
	    		);
	    	$analytics->setDimensions(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_INTERACTION_NETWORK,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_INTERACTION_NETWORK_ACTION  			
	    		)
	    		);
	    	$analytics->setMax(500);
	    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
	    	$analytics->setSortDirection(true);
    		switch($this->_getParam('segment')){
    		case 'mobile':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    			break;
    		case 'tablet':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_TABLET_TRAFFIC);
    			break;
    		default:
    			break;
    	}
	    	$this->view->results = $analytics->getData();
    	} 
    	
    	public function activitiesAction()
    	{
    		$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
	    	$analytics->setProfile(25726058);
	    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
	    	$dates = $timeframe->getDates();
	    	$analytics->setStart($dates['start']);
	    	$analytics->setEnd($dates['end']);
	    	$analytics->setMetrics(array(
	    		Zend_Gdata_Analytics_DataQuery::METRIC_SOCIAL_ACTIVITIES
	    		)
	    		);
	    	$analytics->setDimensions(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_CONTENT_URL,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_POST,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_NETWORK_ACTION,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_DISPLAY_NAME,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_ENDORSING_URL
	    		)
	    		);
	    	$analytics->setMax(self::MAX_RESULTS);
	    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SOCIAL_ACTIVITIES);
	    	$analytics->setSortDirection(true);
	    	$analytics->setStartIndex($this->getStart());
	    	$this->view->results = $analytics->getData();
			$paginator = Zend_Paginator::factory((int)$analytics->getTotal());
	        $paginator->setCurrentPageNumber((int)$this->getPage())
				->setItemCountPerPage((int)self::MAX_RESULTS);
			$this->view->paginator = $paginator;
    	}
    	
    	public function networksAction()
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
	    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
	    		)
	    		);
	    	$analytics->setDimensions(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK
	    		)
	    		);
	    	$analytics->setMax(500);
	    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
	    	$analytics->setFilters(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . '(not set)'
	    	));	    	
	    	$analytics->setSortDirection(true);
	    	$this->view->results = $analytics->getData();
    	}
    	
    	public function mobilesAction()
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
	    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
	    		)
	    		);
	    	$analytics->setDimensions(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING
	    		)
	    		);
	    	$analytics->setMax(500);
	    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
	    	if($this->_getParam('network',false)){
	    	$analytics->setFilters(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_getParam('network')
	    	));	  
	    	}
	    	$analytics->setSegment(11);  	
	    	$analytics->setSortDirection(true);
	    	$this->view->results = $analytics->getData();
    	}
    	
    	public function tabletsAction()
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
	    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
	    		)
	    		);
	    	$analytics->setDimensions(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING
	    		)
	    		);
	    	$analytics->setMax(500);
	    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
	    	if($this->_getParam('network',false)){
	    	$analytics->setFilters(array(
	    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_getParam('network')
	    	));	  
	    	}
	    	$analytics->setSegment(13);  	
	    	$analytics->setSortDirection(true);
	    	$this->view->results = $analytics->getData();
    	}
    	
}

