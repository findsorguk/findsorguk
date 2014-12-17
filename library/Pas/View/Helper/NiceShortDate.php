<?php
/**
 * A view helper for displaying a short human readable date
 *
 * Example use:
 * <code>
 * <?php
 * echo $this->niceShortDate()->setDate($date);
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 */

class Pas_View_Helper_NiceShortDate extends Zend_View_Helper_Abstract
{
    /** The date string
     * @access protected
     * @var string
     */
    protected $_date;

    /** Get the date to query
     * @access public
     * @return string
     */
    public function getDate() {
        return $this->_date;
    }

    /** Set the date
     * @access public
     * @param string|int $date
     * @return \Pas_View_Helper_NiceShortDate
     */
    public function setDate($date) {
        $this->_date = $date;
        return $this;
    }

    /**
     * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
     *
     * @param  string $date_string Datetime string
     * @return string Formatted date string
     * @access public
     */
    private function fromString($date_string) {
        if (is_integer($date_string) || is_numeric($date_string)) {
            return intval($date_string);
        } else {
            return strtotime($date_string);
        }
    }
    /** Format the date as wanted for this view helper
     * @access public
     * @param  string $date_string
     * @return string $ret
     */
    public function __toString() {
        $date = $this->fromString($this->getDate());
        $ret = '';
        if($date) {
            $ret .= date('l jS F Y', $date);
        } else {
            $ret = 'N/A';
        }
        return $ret;
    }
    /** The function
     * @author Daniel Pett <dpett@britishmuseum.org>
     * @access public
     * @return \Pas_View_Helper_NiceShortDate
     */
    public function niceShortDate() {
        return $this;
    }
}