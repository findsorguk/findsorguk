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
class Analytics_ContentController 
    extends Pas_Controller_Action_Admin {
    
    const MAX_RESULTS = 20;
    
    public function init(){
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
	
    public function overviewAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE,
    		Zend_Gdata_Analytics_DataQuery::METRIC_ENTRANCES,
    		Zend_Gdata_Analytics_DataQuery::METRIC_EXITS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
    		
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,		
    		)
    		);
    	if(is_null($this->_getParam('filter'))){
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . 'forum'
	    ));
	    } else {
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::REGULAR . '/' .$this->_getParam('filter')
	    ));
	    }
    	$analytics->setMax(20);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$analytics->setStartIndex($this->getStart());
    	$this->view->results = $analytics->getData();
		$paginator = Zend_Paginator::factory((int)$analytics->getTotal());
        $paginator->setCurrentPageNumber((int)$this->getPage())
			->setItemCountPerPage((int)self::MAX_RESULTS);
		$this->view->paginator = $paginator;
    }

    public function pageAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE,
    		Zend_Gdata_Analytics_DataQuery::METRIC_ENTRANCES,
    		Zend_Gdata_Analytics_DataQuery::METRIC_EXIT_RATE,
    		Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
    		)
    	);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,
    		)
    		);
    	if(is_null($this->_getParam('url'))){
    	throw new Pas_Analytics_Exception('A path must be set');
	    } else {
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
	    . Zend_Gdata_Analytics_DataQuery::EQUALS 
	    . base64_decode(rawurldecode($this->_getParam('url')))
	    ));
	    }
    	$analytics->setMax(20);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	
    	$this->view->results = $analytics->getData();
    	$this->view->total = (int)$analytics->getTotal();
    	$this->view->path = $this->_getParam('url');
    }
}

