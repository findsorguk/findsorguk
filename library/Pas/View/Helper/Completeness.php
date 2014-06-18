<?php
/**
 * A view helper for rendering completeness of object
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Validate_Int
 */
class Pas_View_Helper_Completeness extends Zend_View_Helper_Abstract {

    /** The integer value to lookup
     *
     * @var int
     */
    protected $_int;

    /** The validator to use
     *
     * @var object
     */
    protected $_validator;
    
    /** Get the id to query
     * @access public
     * @return int
     */
    public function getInt() {
        return $this->_int;
    }
    
    /** Get the validator
     * @access public
     * @return object
     */
    public function getValidator() {
        $this->_validator = Zend_Validate_Int();
        return $this->_validator;
    }

    /** Set the ID to query
     * @access public
     * @param int $int
     * @return \Pas_View_Helper_Completeness
     */
    public function setInt( int $int) {
        $this->_int = $int;
        return $this;
    }

    /** The completeness function
     *
     * @return \Pas_View_Helper_Completeness
     */
    public function completeness() {
        return $this;
    }

    /** Generate the html
     *
     * @return string
     */
    public function html(){
        $this->validate();
        switch ($this->getInt()) {
            case 1:
                $comp = 'Fragment';
		break;
            case 2:
                $comp = 'Incomplete';
		break;
            case 3:
		$comp = 'Uncertain';
		break;
            case 4:
		$comp = 'Complete';
		break;
            default:
		$comp = 'Invalid completeness specified';
		break;
	}
	return $comp;
    }

    /** Validate the value provided
     *
     * @return \Pas_View_Helper_Completeness
     * @throws Zend_Exception
     */
    public function validate(){
        if($this->getValidator()->isValid($this->getInt())) {
            return $this;
        } else {
            throw new Zend_Exception('Invalid value specified');
        }
    }

    /** Magic method to return the string
     *
     * @return function
     */
    public function __toString() {
        return $this->html();
    }
}