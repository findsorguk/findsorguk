<?php
/**
 * GaContentMetrics helper for rendering metrics for a certain page
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->geContentMetrics()
 * ->setId($id)
 * ->setPassword($password)
 * ->setProfile($profile)
 * ->setPath($path)
 * ->setLimit($limit)
 * ->setTimeSpan($span);
 * </code>
 * 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/analytics/views/scripts/content/page.phtml
 */
class Pas_View_Helper_GaContentMetrics extends Zend_View_Helper_Abstract
{
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
     * @return \Pas_View_Helper_GaContentMetrics
     */
    public function gaContentMetrics(){
        return $this;
    }

    /** Get the data from google's api
     * @access public
     * @return \Pas_View_Helper_GaContentMetrics
     */
    public function getGaData() {
        $analytics = new Pas_Analytics_Gateway($this->getId(), $this->getPassword());
        $analytics->setProfile($this->getProfile());
        $timeframe = new Pas_Analytics_Timespan($this->getTimeSpan());
        $dates = $timeframe->getDates();
        $analytics->setStart($dates['start']);
        $analytics->setEnd($dates['end']);
        $analytics->setMetrics(array(
            Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME,
            Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_METRICS_SAMPLE

            )
            );
        $analytics->setDimensions(array(
            Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH,
            )
            );
        $analytics->setFilters(array(
        Zend_Gdata_Analytics_DataQuery::DIMENSION_PAGE_PATH 
                . Zend_Gdata_Analytics_DataQuery::EQUALS . $this->_path
        ));
        $analytics->setMax($this->getLimit());
        $analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME);
        $analytics->setSortDirection(true);
        $this->_results = $analytics->getData();
        $this->_total = $analytics->getTotal();
        return $this;
    }

    /** Get the data to render for to string function
     * @access public
     * @return string
     */
    public function render()  {
        $this->getGaData();
        $html = '';
        if ($this->_total > 0) {
        $html .= '<div class="row-fluid"><h3 class="lead">Average page load time:</h3><ul>';
        foreach ($this->_results as $row) {
        $html .= '<li>' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME);
        $html .= ' seconds from a sample size of: ' . $row->getMetric(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_METRICS_SAMPLE) . '</li>';
        }
        $html .= '</ul></div>';
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
