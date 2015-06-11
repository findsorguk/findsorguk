<?php
/**
 * A view helper for determining the difference between dates for expired jobs
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->expiredJob()->setDate($this->expire)
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 */

class Pas_View_Helper_ExpiredJob extends Zend_View_Helper_Abstract
{

    /** The date string
     * @access protected
     * @var date
     */
    protected $_date;

    /** Today as a string
     * @access protected
     * @var date
     */
    protected $_today;

    /** Get the date to query
     * @access public
     * @return date
     */
    public function getDate() {
        return $this->_date;
    }

    /** Set the date to query
     * @access public
     * @return date
     */
    public function setDate($date) {
        $this->_date = $date;
        return $this;
    }

    /** Get today's date
     * @access public
     * @return type
     */
    public function getToday() {
        $this->_today = new Zend_Date(null,'YYYY-MM-dd');
        return $this->_today;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ExpiredJob
     */
    public function expiredJob() {
        return $this;
    }

    /** Check the date
     * @access public
     * @return boolean
     */
    public function checkDate() {
        $difference = $this->getToday()->isLater(
                new Zend_Date($this->getDate(),'YYYY-MM-dd')
                );
        return $difference;
    }
}
