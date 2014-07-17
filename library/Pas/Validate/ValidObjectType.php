<?php 
/** A validation class for checking for valid British National Grid values
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category   Pas
 * @package    Pas_Validate
 */
class Pas_Validate_ValidObjectType extends Zend_Validate_Abstract {
    
    /** The not valid constant
     * 
     */
    const NOT_VALID = 'notValid';

    /** Validation failure message template definitions
    * @access protected
    * @var array
    */
    protected $_messageTemplates = array(
        self::NOT_VALID => 'You can only use terms in the database. 
These appear in the autocomplete in block capitals.');

    /**  A function to flatten an array
     * @access public
     * @param array $ar
     * @return array
     */
    public function flatten(array $ar) {
        $toflat = array($ar);
        $res = array();
        while (($r = array_shift($toflat)) !== null) {
            foreach ($r as $v) {
                if (is_array($v)) {
                    $toflat[] = $v;
                    } else {
                        $res[] = $v;
                    }
            }
        }
        return $res;
    }

    /** Get a list of object types and then flatten the array
     * @access public
     * @return array
     */
    public function getTypes(){
        $objects = new ObjectTerms();
        $o =  $objects->getObjectNames();
        return $this->flatten($o);
    }

    /** Check if value is in the array, needle in the haystack style.
     * @access public
     * @param string $needle
     * @param array $haystack
     * @return boolean
     */
    public function in_arrayi( $needle, array $haystack ) { 
        $found = false; 
        foreach( $haystack as $value ) { 
            if( strtolower( $value ) === strtolower( $needle ) ) { 
                $found = true; 
            } 
        }    
        return $found; 
    }

    /** Check if value is valid
     * @access public
     * @param string $value
     * @return boolean
     */
    public function isValid($value){
        $objecttypes = $this->getTypes();
        if(!$this->in_arrayi($value,$objecttypes)) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }
}