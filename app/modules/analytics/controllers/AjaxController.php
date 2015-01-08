<?php
/** A controller for pulling data from the google analytics api for use in 
 * ajax contexts.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_Gdata_Analytics
 * @uses Pas_Analytics_Timespan
 * @uses Pas_Analytics_Gateway
 * @uses Zend_Gdata_Analytics_DataQuery
 * 
 */
class Analytics_AjaxController extends Pas_Controller_Action_Admin {
    	
    /** Initialise the acl and other variables
     * @access public
     */
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_id = $this->_helper->config()->webservice->google->username;
        $this->_pword = $this->_helper->config()->webservice->google->password;
        $this->_service = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
        $this->_helper->layout->disableLayout();
    }

    /** Create markers for mapping
     * @access public
     */
    public function markersAction(){
        $analytics = new Pas_Analytics_Gateway($this->_id, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS));
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_LATITUDE,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_LONGITUDE)
                );
        $analytics->setMax(500);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSortDirection(true);
        switch($this->getParam('segment')){
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

    /** Get the number of page visitors
     * @access public
     * @throws Pas_Analytics_Exception
     */
    public function pagevisitorsAction(){
        $analytics = new Pas_Analytics_Gateway($this->_id, $this->_pword);
        $analytics->setProfile(25726058);
        $timeframe = new Pas_Analytics_Timespan(); 
        $timeframe->setTimespan($this->getParam('timespan'));
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
                Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
                )
                );
        $analytics->setDimensions(array(
                Zend_Gdata_Analytics_DataQuery::DIMENSION_LATITUDE,
                Zend_Gdata_Analytics_DataQuery::DIMENSION_LONGITUDE,
                )
                );
        if(is_null($this->getParam('url'))){
        throw new Pas_Analytics_Exception('A path must be set');
            } else {
        $analytics->setFilters(array(
            Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
            . Zend_Gdata_Analytics_DataQuery::EQUALS 
            . base64_decode(rawurldecode($this->getParam('url')))
            ));
            }
        $analytics->setMax(100);
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSortDirection(true);
        $this->view->results = $analytics->getData();
    }
}