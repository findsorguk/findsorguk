<?php
/**
 * GaContentPreviousPage helper
 * 
 * An example of use:
 * <code>
 * <?php
 * echo $this->gaContentPreviousPage()
 * ->setPath($path)
 * ->setLimit($limit)
 * ->setTimespan($timespan)
 * ->setId($username)
 * ->setPassword($password);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @uses viewHelper Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/analytics/views/scripts/content/page.phtml
 * @todo Decide whether to deprecate this function
 * 
 */
class Pas_View_Helper_GaContentPreviousPage extends Zend_View_Helper_Abstract {
    
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
    
    /** The profile to query
     * @access protected
     * @var int
     */
    protected $_profile;
    
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

    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_GaContentPreviousPage
     */
    public function gaContentPreviousPage() {
        return $this;
    }
        
    /** Get the data from the api
     * @access public
     * @return \Pas_View_Helper_GaContentPreviousPage
     */
    public function getGaData() {
        $analytics = new Pas_Analytics_Gateway(
                $this->getId(), 
                $this->getPassword()
                );
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
            Zend_Gdata_Analytics_DataQuery::DIMENSION_PREV_PAGE_PATH,
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

    /** Send the data to html string
     * @access public
     * @return string
     */
    public function render() {
        $this->getGaData();
        $html = '<div class="span5"><table class="table table-striped">';
        $html .= '<caption>Previous pages to viewing this one</caption>';
        $html .= '<thead><tr><th>Previous page</th><th>Visitors</th><th>Page views</th><th>Avg time on page</th></tr>';
        $html .= '</thead><tbody>';
        foreach ($this->_results as $row) {
            $html .= '<tr><td><a href="'. $this->view->serverUrl() . $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH) . '">' . $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_TITLE) . '</a></td>';
            $html .= '<td>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS) . '</td>';
            $html .= '<td>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS) . '</td>';
            $html .= '<td>' . $this->view->secondsToMinutes()->setSeconds($row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_AVG_TIME_ON_PAGE)) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';
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
