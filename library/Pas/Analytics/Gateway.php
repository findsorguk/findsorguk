<?php
/** The gateway wrapper for interfacing with the google analytics system
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
 * $analytics->setProfile(25726058);
 * $analytics->setStart($dates['start']);
 * $analytics->setEnd($dates['end']);
 * $analytics->setMetrics(array(
 * 		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
 *  		Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
 *  		Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
 *  		Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES,
 *  		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
 *  		)
 *     		);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Analytics
 * @version 1
 * @uses Zend_Gdata_Analytics
 * @uses Zend_Gdata_ClientLogin
 * @uses Zend_Registry
 * @example /app/modules/analytics/controllers/AudienceController.php
 * @see https://developers.google.com/analytics/devguides/reporting/core
 * @see http://ga-dev-tools.appspot.com/explorer/
 */
class Pas_Analytics_Gateway {
	
    /** The service client
     * @access protected
     * @var \Zend_Gdata_Analytics
     */
    protected $_client;

    /** The api
     * @access protected
     * @var type 
     */
    protected $_analytics;

    /** The query to send
     * @access protected
     * @var type 
     */
    protected $query;

    /** The metrics to query api with
     * @access protected
     * @var array
     */
    protected $metrics;

    /** The dimensions to query
     * @access protected
     * @var array
     */
    protected $dimensions;

    /** The segment to query
     * @access protected
     * @var string
     */
    protected $segment;

    /** The sort to apply
     * @access protected
     * @var string
     */
    protected $sort;

    /** The direction of sort
     * @access protected
     * @var string
     */
    protected $direction;

    /** Whether to cache
     * @access protected
     * @var boolean
     */
    protected $_cache;

    /** The data response
     * @access protected
     * @var array
     */
    protected $_data;

    /** The beginning date
     * @access protected
     * @var string
     */
    protected $_begin;

    /** The analytics service name
     * 
     */
    CONST SERVICE = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;

    /** The maximum number of metrics to query
     * 
     */
    CONST METRICS = 10;

    /** Max number of dimensions to query
     * 
     */
    CONST DIMENSIONS = 7;

    /** The string to use as a segment prefix
     * 
     */
    const SEGMENT_PREFIX = 'gaid::-';
    
    /** Segment number for all visits
     * 
     */
    const SEGMENT_ALL_VISITS = 1;
    
    /** Segment number for new visitors
     * 
     */
    const SEGMENT_NEW_VISITORS = 2;
    
    /** The segment number for returning visitors
     * 
     */
    const SEGMENT_RETURNING_VISITORS = 3;
    
    /** The segment number for paid search
     * 
     */
    const SEGMENT_PAID_SEARCH_TRAFFIC = 4; 
    
    /** The segment number for unpaid traffic
     * 
     */
    const SEGMENT_NO_PAID_SEARCH_TRAFFIC = 5;
    
    /** The search traffic segment
     * 
     */
    const SEGMENT_SEARCH_TRAFFIC = 6;
    
    /** Direct traffic segment
     * 
     */
    const SEGMENT_DIRECT_TRAFFIC = 7;
    
    /** Referral segment
     * 
     */
    const SEGMENT_REFERRAL_TRAFFIC = 8;
    
    /** Segment for converted visits
     * 
     */
    const SEGMENT_VISITS_WITH_CONVERSIONS = 9;
    
    /** Segment for visits with transactions
     * 
     */
    const SEGMENT_VISITS_WITH_TRANSACTIONS = 10;
    
    /** Mobile traffic segment
     * 
     */
    const SEGMENT_MOBILE_TRAFFIC = 11;
    
    /** Visits with out a bounce segment
     * 
     */
    const SEGMENT_NON_BOUNCE_VISITS = 12;
    
    /** Tablet traffic segment
     * 
     */
    const SEGMENT_TABLET_TRAFFIC = 13;

    /** Construct the connection
     * @access public
     * @param string $id
     * @param string $password
     */
    public function __construct( $id, $password ){
        $this->_client = Zend_Gdata_ClientLogin::getHttpClient( 
                $id, 
                $password, 
                self::SERVICE 
                );
        $this->_analytics = new Zend_Gdata_Analytics($this->_client);
        $this->_cache = Zend_Registry::get('cache');
    }

    /** Set the profile ID
     * @access public
     * @return T
     */
    public function setProfile( $profile ) {
        //Check if the title is a string
        if ( is_int( $profile ) ) {
            //Trim the title string for excess white space		
            $this->profile = $profile;
        } else {
            throw new Pas_Analytics_Exception('No analytics profile set', 500);
        }
        return $this;
    }

    /** Set the start index number
     * @access public
     * @param integer $begin
     * @return \Pas_Analytics_Gateway
     */
    public function setStartIndex( $begin ){
        $this->_begin = (int)$begin;
        return $this;
    }

    /** Set the start 
     * @access public
     * @param integer $start
     * @return \Pas_Analytics_Gateway
     */
    public function setStart( $start ) {
        $this->start = (int)$start;
        return $this;
    }	

    /** Set the end
     * @access public
     * @param integer $end
     * @return \Pas_Analytics_Gateway
     */
    public function setEnd( $end )  {
        $this->end = (int)$end;
        return $this;
    }

    /** Set the segment to query
     * @access public
     * @param string $segment
     * @return \Pas_Analytics_Gateway
     */
    public function setSegment( $segment ){
        $this->segment = (string) self::SEGMENT_PREFIX . $segment;
        return $this;
    }

    /** Set the sort
     * @access public
     * @param string $sort
     * @return \Pas_Analytics_Gateway
     */
    public function setSort( $sort ){
        $this->sort = $sort;
        return $this;
    }

    /** Set the sort direction
     * @access public
     * @param string $direction
     * @return \Pas_Analytics_Gateway
     */
    public function setSortDirection( $direction = false ){
        $this->direction = $direction;
        return $this;
    }

    /** Set the dimensions for the query
     * @access public
     * @param array $dimensions
     * @return \Pas_Analytics_Gateway
     * @throws Pas_Analytics_Exception
     */
    public function setDimensions( array $dimensions ) {
        if(is_array($dimensions)){
            $this->dimensions = $dimensions;
        }  else {
            throw new Pas_Analytics_Exception('The dimensions must be an array', 500 );
        }
        return $this;
    }

    /** Set the metrics
     * @access public
     * @param array $metrics
     * @return \Pas_Analytics_Gateway
     * @throws Pas_Analytics_Exception
     */
    public function setMetrics( array $metrics )  {
        if(is_array($metrics)){
            $this->metrics = $metrics;
        }  else {
            throw new Pas_Analytics_Exception('The metrics must be an array', 500 );
        }
        return $this;
    }

    /** Set the maximum number of results to return
     * @access public
     * @param integer $max
     * @return \Pas_Analytics_Gateway
     */
    public function setMax( $max ){
        $this->max = $max;
        return $this;
    }

    /** Set the filters to use
     * @access public
     * @param array $filters
     * @return \Pas_Analytics_Gateway
     * @throws Pas_Analytics_Exception
     */
    public function setFilters( array $filters ) {
        if(is_array($filters)){
            $this->filters = $filters;
        }  else {
            throw new Pas_Analytics_Exception('The filters must be an array', 500 );
        }
        return $this;
    }

    /** Get the filters to apply
     * @access public
     * @return \Pas_Analytics_Gateway
     */
    public function getFilters() {
        if(is_array($this->filters)){	
            foreach($this->filters as $filter){
                $this->query->addFilter($filter);
            }
        } 
        return $this;
    }

    /** Get the metrics to query
     * @access public
     * @return \Pas_Analytics_Gateway
     */
    public function getMetrics() {
        foreach($this->metrics as $metric){
            $this->query->addMetric($metric);
        }
        return $this;
    }

    /** Get the dimensions to query
     * @access public
     * @return \Pas_Analytics_Gateway
     */
    public function getDimensions() {
        foreach($this->dimensions as $dimension){
            $this->query->addDimension($dimension);
        }
        return $this;
    }

    /** Get the segments
     * @access public
     * @return boolean|\Pas_Analytics_Gateway
     */
    public function getSegment(){
        if(isset($this->segment)){
            $this->query->setSegment($this->segment);
        } else {
            return false;
        }
        return $this;
    }

    /** Get the max number of results
     * @access public
     * @return \Pas_Analytics_Gateway
     */
    public function getMax(){
        $this->query->setMaxResults($this->max);
        return $this;
    }
    
    /** Get the direction to sort
     * @access public
     * @return \Pas_Analytics_Gateway
     */
    public function getSort(){
        if(!isset($this->direction)){
            $direction = false;
        } else {
            $direction = true;
        }
        $this->query->addSort($this->sort, $direction);
        return $this;
    }

    /** Get where to start on the index
     * @access public
     * @return \Pas_Analytics_Gateway
     */
    public function getStartIndex(){
        $this->query->setStartIndex( $this->_begin);
        return $this;
    }

   /** Decide whether to cache the results
    * @access public
    * @param boolean $cache
    * @return \Pas_Analytics_Gateway
    */
    public function setCache( $cache = true){
        if($cache){	
            $this->_cacheit = Zend_Registry::get('cache');
        }
        return $this;
    }

    /** Get the data fdrom the api
     * @access public
     * @return array
     */
    public function getData() {
        $this->requiredPropertiesExist();
        $this->query = $this->_analytics->newDataQuery()
                ->setProfileId($this->profile) 
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

    /** Check if all required properties have been set
     * @access public
     * @throws Pas_Analytics_Exception
     */
    public function requiredPropertiesExist() {
        //If the url is not set set and a title is set, return false. Url is required.
        if ( ! ( isset( $this->profile )  ) ) {
            throw new Pas_Analytics_Exception('You must set a profile id', 500);
        }
        if( ! ( isset( $this->start)) && ! (isset( $this->end) )){
            throw new Pas_Analytics_Exception('You must set dates', 500);
        }
    }

    /** Get the total results as a string
     * @access public
     * @return string
     */
    public function getTotal() {
        return $this->_data->getTotalResults()->text;
    }
}