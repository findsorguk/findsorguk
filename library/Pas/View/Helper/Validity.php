<?php
/** A view helper to return whether an integer is seen as valid
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @license http://URL GNU
 * @since 1
 * @version 1
 */
class Pas_View_Helper_Validity extends Zend_View_Helper_Abstract
{

    /** The validity integer
     * @access protected
     * @var int
     */
    protected $_valid;

    /** Get the validity int
     * @access public
     * @return int
     */
    public function getValid()
    {
        return $this->_valid;
    }

    /** Set the valdidity
     * @access public
     * @param  int                       $valid
     * @return \Pas_View_Helper_Validity
     */
    public function setValid(int $valid)
    {
        $this->_valid = $valid;

        return $this;
    }

    /** Return the function
     * @access public
     * @return \Pas_View_Helper_Validity
     */
    public function validity()
    {
        return $this;
    }

    /** The string to return
     * @access public
     * @return string
     */
    public function __toString()
    {
        switch ($this->getValid()) {
        case 1:
            $v = 'Valid';
            break;
        default:
            $v = 'Invalid';
            break;
    }

    return $v;
    }
}
