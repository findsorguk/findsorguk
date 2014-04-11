<?php
class Analytics_AjaxController
    extends Pas_Controller_Action_Admin {
    	
    	public function init(){
			$this->_helper->Acl->allow(null);
			$this->_ID = $this->_helper->config()->webservice->google->username;
			$this->_pword = $this->_helper->config()->webservice->google->password;
			$this->_service = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
			$this->_helper->layout->disableLayout();
    	}
    	
//    	public function markersAction(){
//    	$client = Zend_Gdata_ClientLogin::getHttpClient($this->_ID, $this->_pword, $this->_service);
//		$analytics = new Zend_Gdata_Analytics($client); 
//		$query = $analytics->newDataQuery()->setProfileId(25726058) 
//		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)
//		  ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_LATITUDE) 
//		  ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_LONGITUDE) 
//		  ->setStartDate('2013-01-01')   
//		  ->setEndDate('2013-01-31')  
//		  ->addSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS, true) 
//		  ->setMaxResults(500);
//		  if($this->_getParam('segment',false)){
//		  $query->setSegment('gaid::-11');
//		  }
//		$this->view->results = $analytics->getDataFeed($query);
//    	}
    	
    	public function markersAction(){
    		
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
    			Zend_Gdata_Analytics_DataQuery::DIMENSION_LATITUDE,
    			Zend_Gdata_Analytics_DataQuery::DIMENSION_LONGITUDE)
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
    	
    public function pagevisitorsAction(){
    		
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
    			Zend_Gdata_Analytics_DataQuery::DIMENSION_LATITUDE,
    			Zend_Gdata_Analytics_DataQuery::DIMENSION_LONGITUDE,
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
    		$analytics->setMax(100);
    		$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    		$analytics->setSortDirection(true);
    		$this->view->results = $analytics->getData();
    	}
}