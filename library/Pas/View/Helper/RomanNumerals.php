<?php
/**
 * A view helper for turning dates into Roman numerals
 *
 * This view helper takes a year and breaks this down and returns a roman
 * numeral
 *
 * Example:
 *
 * <code>
 * <?php
 * echo $this->romanNumerals()->setDate(2014);
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @example /app/view/structure/footer.phtml Footer file uses this function
 * for copyright dates
 */
class Pas_View_Helper_RomanNumerals extends Zend_View_Helper_Abstract
{

    /** The date to query
     * @access protected
     * @var int
     */
    protected $_date;

    /** The validator to use
     * @access protected
     * @var object
     */
    protected $_validator;

    /** The roman numeral array
     * @access public
     * @var array
     */
    protected $_numerals =  array(
        'M'  => 1000, 'CM' => 900, 'D'  => 500,
        'CD' => 400, 'C'  => 100, 'XC' => 90,
        'L'  => 50, 'XL' => 40, 'X'  => 10,
        'IX' => 9, 'V'  => 5, 'IV' => 4,
        'I'  => 1
        );

    /** Get the date
     * @access public
     * @return type
     */
    public function getDate()  {
        return $this->_date;
    }

    /** Set the date
     * @access public
     * @param int $date
     * @return \Pas_View_Helper_RomanNumerals
     */
    public function setDate($date) {
        $this->_date = $date;
        return $this;
    }

    /** get the validator
     * @access public
     * @return Zend_Validate_Int
     */
    public function getValidator() {
        $this->_validator = new Zend_Validate_Int();
        return $this->_validator;
    }

    /** Get the array of roman numerals
     * @access public
     * @return array
     */
    public function getNumerals(){
        return $this->_numerals;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_RomanNumerals
     */
    public function romanNumerals() {
        return $this;
    }

    /** Validate the date string
     * @access public
     * @param int $date
     * @return boolean
     */
    public function validate($date) {
       $validator =  $this->getValidator();
       if ($validator->isValid( $date )) {
           return true;
       } else {
           return false;
       }
    }

    /** Create the date string in Roman numerals
     * @access public
     * @return function
     */
    public function createDate() {
        $html = '';
        if ( $this->validate( $this->getDate() ) ) {
            $n = intval($date);
            foreach ($this->getNumerals() as $roman => $number) {
                //Divide number to get matches
                $matches = intval($n / $number);
                //assign the roman char * $matches
                $html .= str_repeat($roman, $matches);
                //subtract from the number
                $n = $n % $number;
            }
        }
        return $html;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->createDate();
    }
}
