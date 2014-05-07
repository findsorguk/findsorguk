<?php
/** A view helper for checking if a date has passed
 * @todo Is this class worth keeping?
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @license GNU
 * @since 1
 */
class Pas_View_Helper_ExpiredOrLive extends Zend_View_Helper_Abstract
{

    /** The date to compare
     *
     * @var date
     */
    protected $_date;

    /** Today's date
     *
     * @var date
     */
    protected $_now;

    /** Get today's date
     *
     * @return string
     */
    public function getNow() {
        $this->_now = Zend_Date::now()->toString('yyyy-MM-dd');
        return $this->_now;
    }

    /** Get the date
     *
     * @return date
     */
    public function getDate() {
        return $this->_date;
    }

    /** Set the date
     *
     * @param type $date
     * @return \Pas_View_Helper_ExpiredOrLive
     */
    public function setDate($date) {
        $this->_date = $date;
        return $this;
    }

    /** Magic method for string rendering
     *
     * @return string
     */
    public function __toString() {
        return $this->compare();
    }

    /** The view helper
     *
     * @return \Pas_View_Helper_ExpiredOrLive
     */
    public function expiredOrLive(){
        return $this;
    }

    /** Compare the dates
     *
     * @return string
     */
    public function compare() {
        if( $this->getDate() <= $this->getNow() ) {
            $class = 0;
        } else {
            $class = 1;
        }

        return (string) $class;
    }

}