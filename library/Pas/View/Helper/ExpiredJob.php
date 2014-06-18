<?php 
/**
 * A view helper for determining the difference between dates for expired jobs
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
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
        $this->_today = new Zend_Date(NULL,'YYYY-MM-dd');
        return $this->_today;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ExpiredJob
     */
    public function expiredJob() {
        return $this->checkDate();
    }

    /** Check the date
     * @access public
     * @return boolean
     */
    public function checkDate(){
        $difference = $this->getToday()->isLater(
                new Zend_Date($this->getDate(),'YYYY-MM-dd')
                );
	return $difference;
    }
    
}