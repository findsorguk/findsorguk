<?php 
/** A validation class for checking for valid British National Grid values
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Validate
 * @version 1
 * @license
 */
class Pas_Validate_ValidGridRef extends Zend_Validate_Abstract {
	
    /** Not valid constant
     * 
     */
    const NOT_VALID = 'notValid';
    
    /** the not even constant
     * 
     */
    const NOT_EVEN  = 'notEven';

    /**  Validation failure message template definitions
    * @access protected
    * @var array
    */
    protected $_messageTemplates = array(
        self::NOT_VALID => 'That grid reference does not appear to have valid starting letters.',
        self::NOT_EVEN => 'That grid reference does not appear to be the correct length.'
    );

    /*
    * An array of valid grid reference prefixes
    * @var array
    */
    protected $letters = array(
        'SV', 'SW', 'SX',
        'SY', 'SZ', 'TV',
        'TW', 'SQ', 'SR',
        'SS', 'ST', 'SU',
        'TQ', 'TR', 'SL',
        'SM', 'SN', 'SO',
        'SP','TL', 'TM',
        'SF', 'SG', 'SH',
        'SJ', 'SK', 'TF',
        'TG', 'SA', 'SB',
        'SC', 'SD', 'SE',
        'TA', 'TB', 'NV',
        'NW', 'NX', 'NY',
        'NZ', 'OV', 'OW',
        'NQ', 'NR', 'NS',
        'NT', 'NU', 'OQ',
        'OR', 'NL', 'NM',
        'NN', 'NO', 'NP',
        'OL', 'OM', 'NF',
        'NG', 'NH', 'NJ',
        'NK', 'OF', 'OG',
        'NA', 'NB', 'NC',
        'ND', 'NE', 'OA',
        'OB', 'HV', 'HW',
        'HX', 'HY', 'HZ',
        'JV', 'JW', 'HQ',
        'HR', 'HS', 'HT',
        'HU', 'JQ', 'JR',
        'HL', 'HM', 'HN',
        'HO', 'HP', 'JL',
        'JM',
    );

    /** Check the length of the NGR
     * @access public
     * @param int $length
     * @return boolean
     */
    public function _checkNum($length){
        return ($length%2) ? TRUE : FALSE;
    }

    /** Check validity of the value
     * @access public
     * @param string $value
     * @return boolean
     */
    public function isValid($value){
        $value = str_replace(' ','',$value);
        $length = strlen($value);
        if($this->_checkNum($length) === true) {
            $this->_error(self::NOT_EVEN);
            return false;		
        }
        //strips off first two characters as National grid has 2 left
        $letterpair = substr($value,0,2); 
        //transform smallcase to capital
        $letterpair = strtoupper($letterpair); 
        //Check if the letter prefix is in the letters array above
        if(!in_array($letterpair,$this->letters)) {
            $this->_error(self::NOT_VALID);
            return false;
        }
        return true;
    }
}