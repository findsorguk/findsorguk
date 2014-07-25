<?php
/**
 * GaHeadlineStats helper for generating the headline stats for an account
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->gaHeadlineStats()
 * ->setId($id)
 * ->setProfile($profile)
 * ->setPassword($password)
 * ->setSegment($segment)
 * ->setStart($start)
 * ->setEnd($end);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @uses viewHelper Pas_View_Helper
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo Put in checks for date strings.
 * @example /app/modules/analytics/views/scripts/index/index.phtml 
 *
 */
class Pas_View_Helper_GaHeadlineStats extends Zend_View_Helper_Abstract
{
    /** The client for accessing analytics api
     * @access protected
     * @var object
     */
    protected $_client;

    /** The analytics wrapper
     * @access protected
     * @var object
     */
    protected $_analytics;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The service to use
     *
     */
    CONST SERVICE = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;

    /** The segment prefix
     *
     */
    CONST SEGMENTPREFIX = 'gaid::';

    /** The google id to use
     * @access protected
     * @var string
     */
    protected $_id;

    /** The password to use
     * @access protected
     * @var string
     */
    protected $_password;

    /** The profile to use
     * @access protected
     * @var string
     */
    protected $_profile;

    /** The start date
     * @access protected
     * @var string
     */
    protected $_start;

    /** The end date
     * @access protected
     * @var string
     */
    protected $_end;

    /** The segment to query
     * @access protected
     * @var string
     */
    protected $_segment;

    /** Get the id to use
     * @access public
     * @return string
     */
    public function getId() {
        return $this->_id;
    }

    /** Get the password
     * @access public
     * @return string
     */
    public function getPassword() {
        return $this->_password;
    }

    /** Set the ID
     * @access public
     * @param string $id
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** Set the password
     * @access public
     * @param string $password
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function setPassword($password) {
        $this->_password = $password;
        return $this;
    }

    /** Get the client to query
     * @access public
     * @return \Zend_Gdata_ClientLogin
     */
    public function getClient() {
        $this->_client = Zend_Gdata_ClientLogin::getHttpClient(
                $this->getId(),
                $this->getPassword(),
                self::SERVICE
                );
        return $this->_client;
    }

    /** Get the analytics class
     * @access public
     * @return \Zend_Gdata_Analytics
     */
    public function getAnalytics() {
        $this->_analytics = new Zend_Gdata_Analytics($this->getClient());
        return $this->_analytics;
    }

    /** Get the cache
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the segment
     * @access public
     * @return string
     */
    public function getSegment() {
        return $this->_segment;
    }

    /** The class to return
     * @access public
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function gaHeadlineStats(){
        return $this;
    }

    /** Set the profile
     * @access public
     * @param int $profile
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function setProfile($profile) {
        //Check if the title is a string
        if ( is_int( $profile ) ) {
            //Trim the title string for excess white space
            $profile = trim( $profile );
            $this->_profile = $profile;
        }
        return $this;
    }

    /** Set the start date
     * @access public
     * @param string $start
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function setStart( $start ) {
        $this->_start = $start;
        return $this;
    }

    /** Set the end date
     * @access public
     * @param string $end
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function setEnd($end) {
        $this->_end = $end;
        return $this;
    }

    /** Set the segement
     * @access public
     * @param string $segment
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function setSegment($segment) {
        $this->_segment = self::SEGMENTPREFIX . $segment;
        return $this;
    }

    /** Get the profile to query
     * @access public
     * @return string
     */
    public function getProfile() {
        return $this->_profile;
    }

    /** Get the start date
     * @access public
     * @return string
     */
    public function getStart() {
        return $this->_start;
    }

    /** Get the end date
     * @access public
     * @return string
     */
    public function getEnd() {
        return $this->_end;
    }


    /** Get the data from the api
     * @access public
     * @return \Pas_View_Helper_GaHeadlineStats
     */
    public function getData() {
        $key = md5(
                $this->getProfile()
                . $this->getStart()
                . $this->getEnd()
                . $this->getSegment());
        if (!($this->getCache()->test($key))) {

            $query = $this->getAnalytics()->newDataQuery()->setProfileId(
                $this->getProfile()
                )
              ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)
              ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)
              ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)
              ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE)
              ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_NEW_VISITS)
              ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)
              ->setStartDate($this->getStart())
              ->setEndDate($this->getEnd()) ;
            if (!is_null($this->getSegment())) {
            $query->setSegment($this->getSegment());
            }
            $this->data = $this->getAnalytics()->getDataFeed($query);
            $this->getCache()->save($this->data);
                } else {
            $this->data = $this->getCache()->load($key);
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
        if ( !is_null( $this->getProfile()   ) ) {
            return false;
        }
        if ( !is_null ( $this->getStart()) && !is_null( $this->getEnd() )) {
            return false;
        }
        //If none of the above conditions are met then return test positive
        return $this->getData();
    }

    public function toArray() {
        //Check if all the required properties have been set for the card metadata you are building
        if ( !$this->requiredPropertiesExist() ) {
            return array();
        }
        foreach ($this->data as $row) {
        $time = new Zend_Date($row->getMetric(
                Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE)->value/
                $row->getMetric(
                        Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)->value,
                Zend_Date::SECOND);
        $length = $time->toString('mm.ss');
        $analytics = array(
        'page views' => number_format(
                $row->getMetric(
                        Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)->value
                ),
        'unique page views' => number_format(
                $row->getMetric(
                        Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)->value
                ),
        'visits' => number_format(
                $row->getMetric(
                        Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)->value
                ),
        'visitors' => number_format(
                $row->getMetric(
                        Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)->value
                ),
        'time on site' => $length
        );
        }
        return $analytics;
    }

    /** To string
     * @access public
     * @return string|boolean
     */
    public function __toString() {
        $html = '';
        $data = $this->toArray();
        if ( empty( $data ) ) {
            return false;
        }
        $html .= '<ul>';
        foreach ($data as $name => $value) {
            $html .= '<li>';
            $html .= ucfirst($name);
            $html .= ': ';
            $html .= $value;
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}