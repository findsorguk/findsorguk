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
class Pas_View_Helper_GaContentSearch extends Zend_View_Helper_Abstract {
	
	protected $_path;
	
	protected $_limit;
	
	protected $_id;
	
	protected $_total;
	
	public function __construct(){
		$config = Zend_Registry::get('config');
		$this->_id = $config->webservice->google->username;
		$this->_password = $config->webservice->google->password;
	}
	public function gaContentSearch() {
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
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS,
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE,
    		Zend_Gdata_Analytics_DataQuery::METRIC_ENTRANCES,
    		
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE,
    		)
    		);
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . '(not set)',
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . '(not provided)',
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::REGULAR . $this->_path
	    ));
    	$analytics->setMax($this->getLimit());
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->_results = $analytics->getData();
    	$this->_total = $analytics->getTotal();
    	return $this;	
	}
	
	public function render()
	{
		$this->getGaData();
		if($this->_total > 0){
		$html = '<div class="row-fluid"><h3 class="lead">Top 10 search phrases that led here:</h3><table class="table table-striped">';
		$html .= '<caption>Keywords</caption>';
		$html .= '<thead><tr><th>Keyword</th><th>Source</th><th>Visitors</th><th>Page views</th><th>Avg time on page</th></tr>';
		$html .= '</thead><tbody>';
		foreach($this->_results as $row){
			$html .= '<tr><td>' . $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD) . '</td>';
			$html .= '<td>' . $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE) . '</td>';
			$html .= '<td>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS) . '</td>';
			$html .= '<td>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS) . '</td>';
			$html .= '<td>' . $this->view->secondsToMinutes()->setSeconds($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE)) . '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table></div>';
		return $html;
		} else {
			return '';
		}
	}
	
	public function __toString(){
		return $this->render();
	}
}

