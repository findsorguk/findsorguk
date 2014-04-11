<?php
/**
 * 
 * @author dpett
 * @see https://developers.google.com/analytics/devguides/reporting/core
 * @see http://ga-dev-tools.appspot.com/explorer/
 */
class Pas_Analytics_Gateway {
	
	protected $_client;
	
	protected $_analytics;
	
	protected $query;
	
	protected $metrics;
	
	protected $dimensions;
	
	protected $segment;
	
	protected $sort;
	
	protected $direction;
	
	protected $_cache;
	
	protected $_data;
	
	protected $_begin;
	
	CONST SERVICE = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
	
	CONST METRICS = 10;
	
	CONST DIMENSIONS = 7;
	
	const SEGMENT_PREFIX = 'gaid::-';
	const SEGMENT_ALL_VISITS = 1;
	const SEGMENT_NEW_VISITORS = 2;
	const SEGMENT_RETURNING_VISITORS = 3;
	const SEGMENT_PAID_SEARCH_TRAFFIC = 4; 
	const SEGMENT_NO_PAID_SEARCH_TRAFFIC = 5;
	const SEGMENT_SEARCH_TRAFFIC = 6;
	const SEGMENT_DIRECT_TRAFFIC = 7;
	const SEGMENT_REFERRAL_TRAFFIC = 8;
	const SEGMENT_VISITS_WITH_CONVERSIONS = 9;
	const SEGMENT_VISITS_WITH_TRANSACTIONS = 10;
	const SEGMENT_MOBILE_TRAFFIC = 11;
	const SEGMENT_NON_BOUNCE_VISITS = 12;
	const SEGMENT_TABLET_TRAFFIC = 13;
	
	
	public function __construct( $id, $password ){
		$this->_client = Zend_Gdata_ClientLogin::getHttpClient( $id, $password, self::SERVICE );
		$this->_analytics = new Zend_Gdata_Analytics($this->_client);
		$this->_cache = Zend_Registry::get('cache');
	}
	
	/**
	 * Set the profile ID
	 * @access public
	 * @return T
	 */
	public function setProfile( $profile )
	{
		//Check if the title is a string
		if ( is_int( $profile ) ) {
			//Trim the title string for excess white space		
			$this->profile = $profile;
		} else {
			throw new Pas_Analytics_Exception('No analytics profile set', 500);
		}
	}
	
	public function setStartIndex( $begin ){
		$this->_begin = $begin;
	}
	
	public function setStart( $start ) 
	{
		$this->start = $start;
	}	
	
	public function setEnd( $end ) 
	{
		$this->end = $end;
	}
	
	public function setSegment( $segment )
	{
		$this->segment = self::SEGMENT_PREFIX . $segment;
	}
	
	public function setSort( $sort )
	{
		$this->sort = $sort;
	}
	
	public function setSortDirection( $direction = false ){
		
		$this->direction = $direction;
	}
	
	public function setDimensions( array $dimensions )
	{
		if(is_array($dimensions)){
			$this->dimensions = $dimensions;
		}  else {
			throw new Pas_Analytics_Exception('The dimensions must be an array', 500 );
		}
	}
	
	public function setMetrics( array $metrics )
	{
		if(is_array($metrics)){
			$this->metrics = $metrics;
		}  else {
			throw new Pas_Analytics_Exception('The metrics set must be an array', 500 );
		}
	}
	
	public function setMax( $max ){
		$this->max = $max;
	}
	
	public function setFilters( array $filters )
	{
		if(is_array($filters)){
			$this->filters = $filters;
		}  else {
			throw new Pas_Analytics_Exception('The filters set must be an array', 500 );
		}
	}
	
	public function getFilters()
	{
		if(is_array($this->filters)){	
		foreach($this->filters as $filter)
		{
			$this->query->addFilter($filter);
		}
		} 
	}
	
	public function getMetrics()
	{
		foreach($this->metrics as $metric){
			$this->query->addMetric($metric);
		}
	}
	
	public function getDimensions()
	{
		foreach($this->dimensions as $dimension){
			$this->query->addDimension($dimension);
		}
	}
	
	public function getSegment(){
		if(isset($this->segment)){
			$this->query->setSegment($this->segment);
		} else {
			return false;
		}
	}
	
	public function getMax(){
		$this->query->setMaxResults($this->max);
		return $this;
	}
	
	public function getSort(){
		if(!isset($this->direction)){
			$d = false;
		} else {
			$d = true;
		}
		$this->query->addSort($this->sort, $d);
	}
	
	public function getStartIndex()
	{
		$this->query->setStartIndex( $this->_begin);
		return $this;
	}
	
	public function setCache( $cache = true){
		if($cache){	
		$this->_cacheit = Zend_Registry::get('cache');
		}
		return $this;
	}
	
	
	public function getData() {
	$this->requiredPropertiesExist();
	$this->query = $this->_analytics->newDataQuery()->setProfileId($this->profile) 
		  ->setStartDate($this->start)   
		  ->setEndDate($this->end);
	$this->getMetrics();
	$this->getDimensions(); 
	$this->getMax();
	$this->getSegment();
	$this->getFilters();
	$this->getStartIndex();
	$this->getSort();
	if($this->_cacheit){
		$key = md5($this->query);
		if (!($this->_cacheit->test($key))) {
			$this->_data = $this->_analytics->getDataFeed($this->query);
		} else {
	$this->_data = $this->_cacheit->load($key);
	}
	} else {
		$this->_data = $this->_analytics->getDataFeed($this->query);
	}
	
	return $this->_data;
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
			throw new Pas_Analytics_Exception('You must set a profile id',500);
		}

		if( ! ( isset( $this->start)) && ! (isset( $this->end) )){
			throw new Pas_Analytics_Exception('You must set dates',500);
		}
		
	}
	
	public function getTotal()
	{
		return $this->_data->getTotalResults()->text;
	}
	

	
}