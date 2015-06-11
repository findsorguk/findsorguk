<?php
/**
 * TimeSpanParameter helper
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->timeSpanParameter();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses viewHelper Pas_View_Helper
 * @example /app/modules/analytics/views/scripts/audience/city.phtml
 */
class Pas_View_Helper_TimeSpanParameter extends Zend_View_Helper_Abstract {
    /** The time span variable
     * @access public
     * @var string
     */
    protected $_timespan;

    /** The request
     * @access public
     * @var \Zend_Controller_Front
     */
    protected $_request;
    
    /** Get the timespan
     * @access public
     * @return string
     */
    public function getTimespan() {
        $params = $this->getRequest();
        $ts = $params['timespan'];
        if (array_key_exists('timespan', $params)) {
            switch ($ts) {
                case 'thisweek':
                    $time = 'this week';
                    break;
                case 'thisyear':
                    $time = 'this year';
                    break;
                case 'lastyear':
                    $time = 'last year';
                    break;
                case 'thismonth':
                    $time = 'this month';
                    break;
                case 'lastmonth':
                    $time = 'last month';
                    break;
                case 'lastweek':
                    $time = 'last week';
                    break;
                default:
                    $time = $ts;
                    break;
            }
            $this->_timespan = $time;
        }
        return $this->_timespan;
    }

    /** The request object
     * @access public
     * @var \Zend_Controller_Front
     */
    public function getRequest() {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        return $this->_request;
    }
    
    /** The function
     * @access public
     * @return \Pas_View_Helper_TimeSpanParameter
     */
    public function timeSpanParameter() {
        return $this;
    }

    /** To string function
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->getTimespan();
    }
}