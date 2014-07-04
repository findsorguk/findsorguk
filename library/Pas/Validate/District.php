<?php
/**
 * A validation class for checking for whether a district provided is valid
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Validate
 * @version 1
 * @see Zend_Validate_Abstract
 * @example path description
 */
class Pas_Validate_District extends Zend_Validate_Abstract {

    /** The not valid message container
     *
     */
    const NOT_VALID = 'notValid';

    /** Validation failure message template definitions
     * @access protected
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_VALID => 'That district does not exist.',
        );

    /** Get whether the district exists in the OS system
     * @access protected
     * @param string $value
     * @return string
     */
    protected function _getDistrict($value){
        $districts = new OsDistricts();
        $where[] = $districts->getAdapter()->quoteInto('osID = ?',$value);
        $district = $districts->fetchRow($where);
        return $district;
    }


    /** Check if the value is valid
     * @access @access public
     * @param string $value
     * @return boolean
     */
    public function isValid($value){
        $value = (string) $value;
        $district = $this->_getDistrict($value);
        if(!$district) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }
}