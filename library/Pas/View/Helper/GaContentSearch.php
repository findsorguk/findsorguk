<?php
/**
 * GaContentSearch helper
 *
 * An example of how to use:
 * 
 * <code>
 * <?php
 * echo $this->gaContentSearch()
 * ->setId($username)
 * ->setPassword($password)
 * ->setPath($url)
 * ->setLimit($limit)
 * ->setTimespan($timespan);
 * ?>
 * </code>
 * 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @license 
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses viewHelper Pas_View_Helper
 * @example /app/modules/analytics/views/scripts/content/page.phtml 
 * @todo Put in check for uri
 */
class Pas_View_Helper_GaContentSearch extends Zend_View_Helper_Abstract
{
    /** The path to query the api with
     * @access protected
     * @var string
     */
    protected $_path;

    /** The default limit of results to return
     * @access protected
     * @var int
     */
    protected $_limit = 10;
    /** The userid to query api with
     * @access protected
     * @var string
     */
    protected $_id;
   
    /** The password to use
     * @access protected
     * @var string
     */
    protected $_password;
    
    /** The total gleaned from the api
     * @access protected
     * @var int
     */
    protected $_total;

    /** get the password set
     * @access public
     * @return string
     */
    public function getPassword() {
        return $this->_password;
    }
    /** Set the password
     * @access public
     * @param string $password
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function setPassword($password) {
       $this->_password = $password;
       return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function gaContentSearch() {
        return $this;
    }

    /** Set the path to query
     * @access public
     * @param string $path
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function setPath($path) {
        $this->_path = base64_decode(rawurldecode($path));
        return $this;
    }

    /** Set the limit of results to return
     * @access public
     * @param int $limit
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function setLimit($limit) {
        if (is_int( $limit )) {
            $this->_limit = $limit;
        } 
        return $this;
    }

    /** Get the limit to return
     * @access public
     * @return int
     */
    public function getLimit() {
        return $this->_limit;
    }

    /** Set the timespan to query
     * @access public
     * @param string $timespan
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function setTimeSpan($timespan)  {
        $this->_timespan = str_replace(' ', '',$timespan);
        return $this;
    }

    /** Get the timespan to query
     * @access public
     * @return string
     */
    public function getTimeSpan()  {
        return $this->_timespan;
    }

    /** Get the path to query
     * @access public
     * @return string
     */
    public function getPath() {
        return $this->_path;
    }

    /** Get the username
     * @access public
     * @return string
     */
    public function getId() {
        return $this->_id;
    }

    
    /** Set the id to query
     * @access public 
     * @param int $id
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }
    
    /** Get the total
     * @access public
     * @return int
     */
    public function getTotal() {
        return $this->_total;
    }
    
    /** The profile to query
     * @access protected
     * @var int
     */
    protected $_profile;
    
    /** Get the profile
     * @access public
     * @return int
     */
    public function getProfile() {
        return $this->_profile;
    }

    /** Set the profile to query
     * @access public
     * @param int $profile
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function setProfile($profile) {
        $this->_profile = $profile;
        return $this;
    }

    
    /** Get data from the api
     * @access public
     * @return \Pas_View_Helper_GaContentSearch
     */
    public function getGaData() {
        $analytics = new Pas_Analytics_Gateway($this->getId(), $this->getPassword());
        $analytics->setProfile($this->getProfile());
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
        Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD 
                . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . '(not set)',
        Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD 
                . Zend_Gdata_Analytics_DataQuery::REGULAR_NOT . '(not provided)',
        Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
                . Zend_Gdata_Analytics_DataQuery::REGULAR . $this->getPath()
        ));
        $analytics->setMax($this->getLimit());
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
        $analytics->setSortDirection(true);
        $this->_results = $analytics->getData();
        $this->_total = $analytics->getTotal();
        return $this;
    }

    /** Create the html to render
     * @access public
     * @return string
     */
    public function render() {
        $html = '';
        $this->getGaData();
        if ($this->_total > 0) {
        $html .= '<div class="row-fluid">';
        $html .= '<h3 class="lead">';
        $html .= 'Top '; 
        $htmk .= $this->getLimit(); 
        $html .= 'search phrases that led here:</h3>';
        $html .= '<table class="table table-striped">';
        $html .= '<caption>Keywords</caption>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Keyword</th><th>Source</th><th>Visitors</th>';
        $html .= '<th>Page views</th><th>Avg time on page</th>';
        $html .= '</tr>';
        $html .= '</thead><tbody>';
        foreach ($this->_results as $row) {
            $html .= '<tr><td>';
            $html .= $row->getDimension(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD
                    );
            $html .= '</td>';
            $html .= '<td>';
            $html .= $row->getDimension(
                    Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE
                    );
            $html .= '</td>';
            $html .= '<td>';
            $html .= $row->getMetric(
                    Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
                    ); 
            $html .= '</td>';
            $html .= '<td>'; 
            $html .= $row->getMetric(
                    Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS
                    );
            $html .= '</td>';
            $html .= '<td>';
            $html .= $this->view->secondsToMinutes()->setSeconds(
                    $row->getMetric(
                            Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE
                            )
                    );
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';
        } 
        return $html;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->render();
    }
}