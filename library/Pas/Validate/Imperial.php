<?php

/**
 * A validation class for checking for whether a RRC id provided is valid
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Validate
 * @version 1
 * @see Zend_Validate_Abstract
 * @use Pas_Curl
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_Validate_Imperial extends Zend_Validate_Abstract
{

    /** The not valid message container
     *
     */
    const NOT_VALID = 'notValid';

    /** Validation failure message template definitions
     * @access protected
     * @var array
     */
    protected $_messageTemplates = array(self::NOT_VALID => 'That is not a valid RIC ID.',);

    /** Check if the value is valid
     * @access @access public
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $type = $this->getType($value);
        if (!$type) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }

    /** Get whether the rrcID exists in the CORR system
     * @access public
     * @param string $value
     * @return string
     */
    public function getType($rrcID)
    {
        $curl = new Pas_Curl();
        $curl->setUri('http://numismatics.org/ocre/id/' . $rrcID);
        $curl->getRequest();
        if($curl->getResponseCode() == '200') {
            return true;
        }
    }
}