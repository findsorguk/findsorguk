<?php
/**
 * A basic view helper for displaying certainty for object types
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Certainty extends Zend_View_Helper_Abstract {


    protected $_validator;

    protected $_certainty;

    /** Construct the objects
     *
     * @param int $int
     */
    public function __construct( $int ) {
        $this->_validator = new Zend_Validate_Int();
        $this->_certainty = $int;
    }

    /** The class
     *
     * @return \Pas_View_Helper_Certainty
     */
    public function certainty() {
        return $this;
    }

    /** Check the validity
     *
     * @return \Pas_View_Helper_Certainty
     * @throws Zend_Exception
     */
    public function _checkValid() {
        if($this->_validator->isValid($this->_certainty)){
            return $this;
        } else {
            throw new Zend_Exception( 'The value supplied must be an integer');
        }
    }

    /** Generate the html
     *
     * @return string
     */
    public function html() {
        $this->_checkValid();

        switch ($this->_certainty) {
		case 1:
                    $html = 'Certain';
                    break;
		case 2:
                    $html = 'Probably';
                    break;
		case 3:
                    $html = 'Possibly';
                    break;
		default:
                    $html = '';
                    break;
		}
	return $html;

    }

    /** Magic method to return string of html
     *
     * @return string
     */
    public function __toString() {
        return $this->html();
    }

}