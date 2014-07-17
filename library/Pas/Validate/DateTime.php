<?php
/** A validator for checking that a date and time is valid
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Validate
 * @version 1
 *
 */
class Pas_Validate_DateTime extends Zend_Validate_Date {

    /** Check if date value submitted is valid
     * @access public
     * @param string $value
     * @return boolean
     */
    public function isValid ($value){
        $this->_setValue($value);
        if (empty($value)) {
            return true;
        }
        $valid = $this->_testDateAgainstFormat($value, $this->getFormat());
        if (!$valid) {
            // re-test for Y-m-d as this format is always a valid option
            $valid = $this->_testDateAgainstFormat($value, 'Y-m-d');
        }
        if ($valid) {
            return true;
        }
        $this->_error(self::INVALID_DATE);
        return false;
    }

    /** Test against the date format supplied
     * @access public
     * @param string $value
     * @param string $format
     * @return boolean
     */
    protected function _testDateAgainstFormat($value, $format){
        $ts = strtotime($value);
        if ($ts !== false) {
            $testValue = date($format, $ts);
            if ($testValue == $value) {
                return true;
            }
        }
        return false;
    }
}