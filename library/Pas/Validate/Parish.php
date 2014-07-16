<?php
/** A validation class for checking for unique usernames
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category   Pas
 * @package    Pas_Validate
 * @license http://URL name
 */
class Pas_Validate_Parish extends Zend_Validate_Abstract {

    /** The not valid constant
     * 
     */
    const NOT_VALID = 'notValid';
    
    /** Validation failure message template definitions
    * @access protected
    * @var array
    */
    protected $_messageTemplates = array(
        self::NOT_VALID => 'That parish does not exist.',
    );

    /** Check the parish exists from the model
     * @access protected
     * @param integer $value
     * @return array
     */
    protected function _getParish($value){
        $parishes = new OsParishes();
        $where[] = $parishes->getAdapter()->quoteInto('osID = ?',$value);
        return $parishes->fetchRow($where);
    } 

    /** Check if valid
     * @access public
     * @param integer $value
     * @return boolean
     */
    public function isValid($value){
        $parish = $this->_getParish($value);
        if(!$parish) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }
}