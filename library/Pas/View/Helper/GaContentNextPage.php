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
class Pas_View_Helper_GaContentNextPage extends Zend_View_Helper_Abstract {
	
	protected $_path;
	
	protected $_limit;
	
	protected $_id;
	
	public function __construct(){
		$config = Zend_Registry::get('config');
		$this->_id = $config->webservice->google->username;
		$this->_password = $config->webservice->google->password;
	}
	public function gaContentNextPage() {
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
	
	public function setTimeSpan( $timespan ){
		$this->_timespan = str_replace(' ', '',$timespan);
		return $this;
	}
	
	public function getGaData(){
		$analytics = new Pas_Analytics_Gateway($this->_id, $this->_password);
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
    		
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_NEXT_PAGE_PATH,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE		
    		)
    		);
    	$analytics->setFilters(array(
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . 'forum',
	    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_path
	    ));
    	$analytics->setMax($this->_limit);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$this->_results = $analytics->getData();
    	return $this;	
	}
	
	public function render()
	{
		$this->getGaData();
		$html = '<div class="span5"><table class="table table-striped">';
		$html .= '<caption>Subsequent pages to viewing this one</caption>';
		$html .= '<thead><tr><th>Next page</th><th>Visitors</th><th>Page views</th><th>Avg time on page</th></tr>';
		$html .= '</thead><tbody>';
		foreach($this->_results as $row){
			$html .= '<tr><td><a href="'. $this->view->serverUrl() . $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH) . '">' . $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE) . '</a></td>';
			$html .= '<td>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS) . '</td>';
			$html .= '<td>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS) . '</td>';
			$html .= '<td>' . $this->view->secondsToMinutes()->setSeconds($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE)) . '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table></div>';
		return $html;
	}
	
	public function __toString(){
		return $this->render();
	}
}

