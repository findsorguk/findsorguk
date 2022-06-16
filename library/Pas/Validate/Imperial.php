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
class Pas_Validate_Imperial extends Pas_Validate_NumismaticsAbstract
{
    /** The error message
     * @access protected
     * @var string
     */
    protected $_url = 'http://numismatics.org/ocre/id/';

    /** Validation failure message template definitions
     * @access protected
     * @var array
     */
    protected $_messageTemplates = array(self::NOT_VALID => 'That is not a valid RIC ID.',
        self::HTTP_ERROR => 'Numismatics - the third party data source - is currently unavailable.' .
            ' Please try again later.');

    /** The error message email subject
     * @access protected
     * @var string
     */
    protected $_errorMessageSubject = 'PAS - Error validating ricID from Numismatics';
}