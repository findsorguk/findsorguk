<?php

/**
 * A view helper for displaying a datestamp as atom date
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 */
class Pas_View_Helper_ToAtomDateStamp extends Zend_View_Helper_Abstract
{
protected $_dateString;

    /** Get the date string
     *
     * @return string
     */
    public function getDateString()
    {
        return $this->_dateString;
    }

    /** Set the date string
     *
     * @param  string                       $dateString
     * @return \Pas_View_Helper_ToDateStamp
     */
    public function setDateString($dateString)
    {
        $this->_dateString = $dateString;

        return $this;
    }

    /** Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string
     * @param  string $date_string Datetime string
     * @return string Formatted date string
     * @access public
     */

    public function fromString($date_string)
    {
    if (is_integer($date_string) || is_numeric($date_string)) {
            return intval($date_string);
    } else {
            return strtotime($date_string);
    }
    }

    /** The function
     *
     * @return \Pas_View_Helper_ToAtomDateStamp
     */
    public function toAtomDateStamp()
    {
        return $this;
    }

    /** The magic method
     *
     * @return string
     *
     */
    public function __toString()
    {
        return $this->format();
    }

    /** Format the date
     *
     * @return string
     */
    public function format()
    {
        $date = $this->fromString($this->getDateString());
        $ret = date('Y-m-d\T', $date);

        return $ret;
    }

}
