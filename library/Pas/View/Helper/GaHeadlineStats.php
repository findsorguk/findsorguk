<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * GaHeadlineStats helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_GaHeadlineStats extends Zend_View_Helper_Abstract{
	
	
	protected $_client;
	
	protected $_analytics;
	
	protected $_cache;
	
	CONST SERVICE = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
	
	CONST SEGMENTPREFIX = 'gaid::';
	
	
	/**
	 * 
	 */
	public function gaHeadlineStats($id, $password ) {
	$this->_client = Zend_Gdata_ClientLogin::getHttpClient($id, $password, self::SERVICE);
	$this->_analytics = new Zend_Gdata_Analytics($this->_client);
	$this->_cache = Zend_Registry::get('cache');
	return $this;
	}
	
	/**
	 * Set the profile ID
	 * @access public
	 * @return T
	 */
	public function setProfile( $profile ) {
		//Check if the title is a string
		if ( is_int( $profile ) ) {
			//Trim the title string for excess white space		
			$profile = trim( $profile );
			$this->profile = $profile;
		}
		return $this;
	}
	
	public function setStart( $start ) {
		$this->start = $start;
		return $this;
	}	
	
	public function setEnd( $end ) {
		$this->end = $end;
		return $this;
	}
	
	public function setSegment( $segment ){
		
		$this->segment = self::SEGMENTPREFIX . $segment;
		return $this;
	}
	
	public function getData() {
	$key = md5($this->profile . $this->start . $this->end . $this->segment);
	if (!($this->_cache->test($key))) {
	$query = $this->_analytics->newDataQuery()->setProfileId($this->profile) 
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_NEW_VISITS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)
		  ->setStartDate($this->start)   
		  ->setEndDate($this->end) ; 
	if(isset($this->segment)){
	$query->setSegment($this->segment);
	}
	$this->data = $this->_analytics->getDataFeed($query);
	$this->_cache->save($this->data);
	} else {
	$this->data = $this->_cache->load($key);
	}	
	return $this;
	}
	
	/**
	 * Check if all required properties have been set
	 * The required properties vary by card type
	 * @access private
	 * @return bool true if all required properties exist for the specified type, else false
	 */
	private function requiredPropertiesExist() {
		
		//If the url is not set set and a title is set, return false. Url is required.
		if ( ! ( isset( $this->profile )  ) ) {
			return false;
		}

		if( ! ( isset( $this->start)) && ! (isset( $this->end) )){
			return false;
		}
		
		//If none of the above conditions are met then return test positive
		return $this->getData();
	}
	
	public function toArray() {
//		//Check if all the required properties have been set for the card metadata you are building
		if ( !$this->requiredPropertiesExist() ) {
			return array();
		}
		foreach($this->data as $row){
		$time = new Zend_Date($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE)->value/$row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)->value, Zend_Date::SECOND);
		$length = $time->toString('mm.ss'); 
		$analytics = array(
		'page views' => number_format($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)->value),
		'unique page views' => number_format($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)->value),
		'visits' => number_format($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)->value),
		'visitors' => number_format($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)->value),
		'time on site' => $length
		);
		}
		return $analytics;
	}
	
	public function __toString()
	{
		
		$data = $this->toArray();
		if ( empty( $data ) ) {
			return false;
		}
		$html = '<ul>';
		foreach ( $data as $name => $value ) {
			$html .= '<li>' . ucfirst($name) . ': ' . $value;
		}
		$html .= '</ul>';
		return $html;
	}
}

