<?php
/**
 * An anslysis of mobile usage of our website via the google analytics api.
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://URL name
 * @version 1
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * 
 */
class Analytics_MobileController extends Pas_Controller_Action_Admin {
    
    /** Initialise the variables and helpers
     * @access public
     */
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
    }

    /** Get the data for the interactions view
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
        $this->view->results = $analytics->getData();
        } 
    }
    /** Get data from the api to list activities
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
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SOCIAL_ACTIVITIES);
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }
}

