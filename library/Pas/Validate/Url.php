<?php
/** A validation class for checking for valid uris
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Zend_Validate
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * 
 */
class Pas_Validate_Url extends Zend_Validate_Abstract {
    
    /** The error constant
     * 
     */
    const INVALID_URL = 'invalidUrl';
 
    /** Validation failure message template definitions
     * @access protected  
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL   => "'%value%' is not a valid URL.",
    );
 
    /* Check if valid
     * @return boolean
     */
    public function isValid($value) {
        if (!Zend_Uri::check($value)) {
            $this->_error(self::INVALID_URL);
            return false;
        }
        return true;
    }
}