<?php
class Pas_Validate_Datetime extends Zend_Validate_Date {
    public function isValid ($value)
    {
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

    protected function _testDateAgainstFormat($value, $format)
    {
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