<?php
/**
 * A basic view helper for displaying certainty for object types
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->certainty()->setCertainty(1);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category   Pas
 * @package View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Certainty extends Zend_View_Helper_Abstract
{
    /** The validator object
     * @access protected
     * @var object
     */
    protected $_validator;

    /** The certainty string
     * @access protected
     * @var string
     */
    protected $_certainty;

    /** Get the validator
     * @access public
     * @return object
     */
    public function getValidator() {
        $this->_validator = new Zend_Validate_Int();
        return $this->_validator;
    }

    /** Get the certainty
     * @access public
     * @return int
     */
    public function getCertainty()  {
        return $this->_certainty;
    }

    /** Set the certainty value
     * @access public
     * @param  int $certainty
     * @return \Pas_View_Helper_Certainty
     */
    public function setCertainty($certainty) {
        $this->_certainty = (int)$certainty;
        return $this;
    }

    /** The class
     * @access public
     * @return \Pas_View_Helper_Certainty
     */
    public function certainty() {
        return $this;
    }

    /** Check validity of certainty
     * @access public
     * @return \Pas_View_Helper_Certainty
     * @throws Zend_Exception
     */
    public function _checkValid()  {
        if ($this->getValidator()->isValid($this->getCertainty())) {
            return $this;
        } else {
            throw new Zend_Exception( 'The value supplied must be an integer');
        }
    }

    /** Render the html
     * @access public
     * @return string
     */
    public function html() {
        $this->_checkValid();
        switch ($this->getCertainty()) {
            case '1':
                $html = 'Certain';
                break;
            case '2':
                $html = 'Probably';
                break;
            case '3':
                $html = 'Possibly';
                break;
            default:
                $html = 'Certain';
                break;
        }
        return $html;
    }

    /** Magic method to return string of html
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->html();
    }
}
