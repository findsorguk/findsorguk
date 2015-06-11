<?php

/**
 * A view helper for displaying a datestamp as atom date
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->toAtomDateStamp()->setDateString($date);
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_View_Helper_Abstract
 * @since September 13 2011
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @example /app/views/scripts/partials/events/eventDetailsSingle.phtml 
 */
class Pas_View_Helper_ToAtomDateStamp extends Zend_View_Helper_Abstract
{

    /** The date string
     * @access protected
     * @var string
     */
    protected $_dateString;

    /** Get the date string
     * @access public
     * @return string
     */
    public function getDateString() {
        return $this->_dateString;
    }

    /** Set the date string
     * @access public
     * @param  string $dateString
     * @return \Pas_View_Helper_ToDateStamp
     */
    public function setDateString($dateString)  {
        $this->_dateString = $dateString;

        return $this;
    }

    /** Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string
     * @param  string $date_string Datetime string
     * @return string Formatted date string
     * @access public
     */
    public function fromString($date_string){
        if (is_integer($date_string) || is_numeric($date_string)) {
                return intval($date_string);
        } else {
                return strtotime($date_string);
        }
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_ToAtomDateStamp
     */
    public function toAtomDateStamp() {
        return $this;
    }

    /** The magic method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->format();
    }

    /** Format the date
     * @access public
     * @return string
     */
    public function format() {
        $date = $this->fromString($this->getDateString());
        $ret = date('Y-m-d\T', $date);
        return $ret;
    }
}