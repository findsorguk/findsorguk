<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * GaContentPageMap helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_GaContentPreviousPage extends Zend_View_Helper_Abstract {
	
	protected $_path;
	
	protected $_limit;
	
	protected $_id;
	
	public function __construct(){
		$config = $this->getInvokeArg('bootstrap')->config;
		$this->_id = $config->webservice->google->username;
		$this->_password = $config->webservice->google->password;
	}
	public function gaContentPreviousPage() {
		return $this;
	}
	
	public function setPath( $path )
	{
		$this->_path = $path;
		return $this;	
	}
	
	public function setLimit( $limit )
	{
		if(is_int( $limit )){
			$this->_limit = $limit;
		} else {
			return false;
		}
		return $this;
	}
	
	public function setTimeSpan( $timespan ){
		$this->_timespan = $timespan;
		return $this;
	}
	
	public function getGaData(){
		$analytics = new Pas_Analytics_Gateway($this->_id, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_timespan);
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
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . 'forum',
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::REGULAR . $this->_path
	    ));
    	$analytics->setMax($this->_limit);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$analytics->setStartIndex($this->getStart());
    	$this->view->results = $analytics->getData();	
	}
	
	
}

