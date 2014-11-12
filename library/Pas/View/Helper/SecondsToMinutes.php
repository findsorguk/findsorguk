<?php

/**
 * SecondsToMinutes helper
 *
 * A view helper to convert seconds to minutes.
 * An example of use:
 * <code>
 * <?php
 * echo $this->secondsToMinutes()->setSeconds($seconds);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/modules/analytics/views/scripts/audience/city.phtml
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_SecondsToMinutes
{

    /** Second to convert
     * @access protected
     * @var int
     */
    protected $_seconds;

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_SecondsToMinutes
     */
    public function secondsToMinutes()
    {
        return $this;
    }

    /** Set the number of seconds to convert
     * @access public
     * @param int $seconds
     * @return \Pas_View_Helper_SecondsToMinutes
     */
    public function setSeconds($seconds)
    {
        $this->_seconds = $seconds;
        return $this;
    }

    /** Convert the seconds
     * @access public
     * @return string
     */
    public function convert()
    {
        $html = '';
        if ($this->_seconds > 0) {
            $time = new Zend_Date($this->_seconds, Zend_Date::SECOND);
            $html .= $time->toString('mm.ss');
        } else {
            $html .= 'cannot be computed';
        }
        return $html;
    }

    /** Render the seconds as a string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->convert();
    }
}
