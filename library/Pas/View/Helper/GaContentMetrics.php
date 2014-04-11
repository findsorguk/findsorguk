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
class Pas_View_Helper_GaContentMetrics extends Zend_View_Helper_Abstract {
	
	protected $_path;
	
	protected $_limit;
	
	protected $_id;
	
	protected $_total;
	
	public function __construct(){
		$config = Zend_Registry::get('config');
		$this->_id = $config->webservice->google->username;
		$this->_password = $config->webservice->google->password;
	}
	public function gaContentMetrics() {
		return $this;
	}
	
	public function setPath( $path )
	{
		$this->_path = base64_decode(rawurldecode($path));
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
	
	public function getLimit()
	{
		return $this->_limit;
	}
	
	public function setTimeSpan( $timespan ){
		$this->_timespan = str_replace(' ', '',$timespan);
		return $this;
	}
	
	public function getTimeSpan(){
		return $this->_timespan;
	}
	
	public function getGaData(){
		$analytics = new Pas_Analytics_Gateway($this->_id, $this->_password);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->getTimeSpan());
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME,
    		Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_METRICS_SAMPLE
    		
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,
    		)
    		);
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_path
	    ));
    	$analytics->setMax($this->getLimit());
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME);
    	$analytics->setSortDirection(true);
    	$this->_results = $analytics->getData();
    	$this->_total = $analytics->getTotal();
    	return $this;	
	}
	
	public function render()
	{
		$this->getGaData();
		if($this->_total > 0){
		$html = '<div class="row-fluid"><h3 class="lead">Average page load time:</h3><ul>';
		foreach($this->_results as $row){
		$html .= '<li>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME);
		$html .= ' seconds from a sample size of: ' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_METRICS_SAMPLE) . '</li>'; 
		}
		$html .= '</ul></div>';
		return $html;
		} else {
			return '';
		}
	}
	
	public function __toString(){
		return $this->render();
	}
}

