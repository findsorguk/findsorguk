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

    /** Construct the object
     *
     * @param integer $int
     */
    public function __construct( $int ) {
        $this->_int = $int;
        $this->_validator = Zend_Validate_Int();
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
        switch ($this->_int) {
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
        if($this->_validator->isValid($this->_int)) {
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