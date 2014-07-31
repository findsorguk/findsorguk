<?php
/** A controller for pulling data from google analytics and displaying social
 * interactions with the website.
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @license
 * @version 1
 *
 *
 */
class Analytics_SocialController extends Pas_Controller_Action_Admin {

    /** Maximum results to return
     *
     */
    const MAX_RESULTS = 20;

    /** Initialise the variables
     * @access public
     */
    public function init() {
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
        
    }

    /** Retrieve the page number
     * @access public
     * @return integer
     */
    public function getPage() {
        $page = $this->_getParam('page');
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

    /** The interactions action
     * @access public
     */
    public function interactionsAction() {
        $analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan();
        $timeframe->setTimespan($this->_getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_INTERACTION_NETWORK,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_INTERACTION_NETWORK_ACTION
                )
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

    /** The activities action
     * @access public
     */
    public function activitiesAction() {
        $analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan();
        $timeframe->setTimespan($this->_getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_SOCIAL_ACTIVITIES
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_CONTENT_URL,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_POST,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_NETWORK_ACTION,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_DISPLAY_NAME,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_ACTIVITY_ENDORSING_URL
                )
                );
        $analytics->setMax(self::MAX_RESULTS);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SOCIAL_ACTIVITIES);
        $analytics->setSortDirection(true);
        $analytics->setStartIndex($this->getStart());
        $this->view->results = $analytics->getData();
        $paginator = Zend_Paginator::factory((int)$analytics->getTotal());
        $paginator->setCache($this->getCache());
        $paginator->setCurrentPageNumber((int)$this->getPage())
                ->setItemCountPerPage((int)self::MAX_RESULTS);
        $this->view->paginator = $paginator;
    }

    /** The networks action
     * @access public
     */
    public function networksAction() {
        $analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan();
        $timeframe->setTimespan($this->_getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK
                )
                );
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setFilters(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK
                . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . '(not set)'
        ));
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }

    /** The mobiles action
     * @access public
     */
    public function mobilesAction() {
        $analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan();
        $timeframe->setTimespan($this->_getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING
                )
                );
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        if($this->_getParam('network',false)){
        $analytics->setFilters(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK
                . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_getParam('network')
        ));
        }
        $analytics->setSegment(11);
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }

    /** The tablets interaction action
     * @access public
     */
    public function tabletsAction() {
        $analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan();
        $timeframe->setTimespan($this->_getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS,
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITS,
                Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_SITE
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_OPERATING_SYSTEM,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING
                )
                );
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        if($this->_getParam('network',false)){
        $analytics->setFilters(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_SOCIAL_NETWORK
                . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_getParam('network')
        ));
        }
        $analytics->setSegment(13);
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }
}