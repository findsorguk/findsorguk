<?php
/** Query the Google analytics api by content
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 */
class Analytics_ContentController  extends Pas_Controller_Action_Admin {
    
    /** The maximum number of results
     * 
     */
    const MAX_RESULTS = 20;
    
    /** Initialise the variables
     * @access public
     */
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
    }
    
    /** Retrieve the page number
     * @access public
     * @return integer
     */
    public function getPage() {
        $page = $this->getParam('page');
        if(!isset($page)){
            $start = 1;
        } else {
            $start = $page;
        }
        return $start;
    }

    /** Get the start number
     * @access public
     * @return integer
     */
    public function getStart() {
        $p = $this->getPage();
        if(is_null($p) || $p == 1){
            $start = 1;
        } else {
            $start = (self::MAX_RESULTS) * ($p - 1) + 1;
        }
        return $start;
    }
    
    /** The index view
     * @access public
     */
    public function indexAction() {
        $this->_helper->redirector('overview');
    }
	
    /** An overview of data
     * @access public
     */
    public function overviewAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE,
                    Zend_Gdata_Analytics_DataQuery::METRIC_ENTRANCES,
                    Zend_Gdata_Analytics_DataQuery::METRIC_EXITS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
    		));
    	$analytics->setDimensions(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE,
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,		
    		));
    	if(is_null($this->getParam('filter'))){
    	$analytics->setFilters(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
                . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . 'forum'
	    ));
	    } else {
    	$analytics->setFilters(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
                . Zend_Gdata_Analytics_DataQuery::REGULAR . '/'
                    . $this->getParam('filter')
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

    /** The page query
     * @access public
     * @throws Pas_Analytics_Exception
     */
    public function pageAction() {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(
                array(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS,
                    Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE,
                    Zend_Gdata_Analytics_DataQuery::METRIC_ENTRANCES,
                    Zend_Gdata_Analytics_DataQuery::METRIC_EXIT_RATE,
                    Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES
    		));
    	$analytics->setDimensions(
                array(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE,
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,
    		));
    	if(is_null($this->getParam('url'))){
    	throw new Pas_Analytics_Exception('A path must be set');
        } else {
            $analytics->setFilters(
                    array(
                        Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
                    . Zend_Gdata_Analytics_DataQuery::EQUALS 
                    . base64_decode(rawurldecode($this->getParam('url')))
	    ));
	    }
    	$analytics->setMax(20);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	
    	$this->view->results = $analytics->getData();
    	$this->view->total = (int)$analytics->getTotal();
    	$this->view->path = $this->getParam('url');
    }
}

