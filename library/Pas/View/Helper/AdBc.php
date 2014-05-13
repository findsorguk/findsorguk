<?php
/**
 * A view helper for turning dates into AD or BC calenderical dates
 * I think that Zend Date can do this as well....
 * @category   	Pas
 * @package    	Pas_View_Helper
 * @subpackage 	Abstract
 * @copyright  	Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    	GNU
 * @see Zend_View_Helper_Abstract
 * @author  Originally TW Bell, extended for Zend by Daniel Pett
 * @version 1
 * @since		26 September 2011
 * @uses Zend_Validate_Int
 */
class Pas_View_Helper_AdBc extends Zend_View_Helper_Abstract
{
    /** The prefix for dates
     *
     * @var string
     */
    protected $_prefix = 'AD';

    /** The suffix for dates
     *
     * @var string
     */
    protected $_suffix = 'BC';

    /** The validator to use
     *
     * @var object
     */
    protected $_validator;

    /** The date to check
     *
     * @var integer
     */
    protected $_date;
    
	/**
	 * @return the $_date
	 */
	public function get_date() {
		return $this->_date;
	}
	
	/**
	 * @return the $_prefix
	 */
	public function get_prefix() {
		return $this->_prefix;
	}

	/**
	 * @return the $_suffix
	 */
	public function get_suffix() {
		return $this->_suffix;
	}

	
		
    /** Construct function
     *
     * @param integer  $date
     */
	public function construct( $date ){
        $this->_validator = new Zend_Validate_Int();
        if($this->_validator->isValid($date)){
            $this->_date = $date;
        }
    }

    
    /** Function for returning the correct date format
     * @access public
     * @return \Pas_View_Helper_AdBc
     */
    public function adBc() {
        return $this;
    }

    /** Function for returning html
     *
     * @return string
     */
    public function html() {
        $html = '';
        $date = $this->get_date();
        if ($date  < 0) {
            $html .= abs($date) . ' ' . $this->get_suffix();
        } else if ($date > 0) {
            $html .= $this->get_prefix() . ' ' . abs($date);
	} else if ($date == 0) {
            $html .= '';
	}
        return $html;
    }
    /** Magic method
     *
     * @return string
     */
    public function __toString() {
        return $this->html();
    }
 }
