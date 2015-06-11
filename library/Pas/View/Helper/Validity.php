<?php

/**
 * A view helper to return whether an integer is seen as valid
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->validity()->setValid(1);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since 1
 * @version 1
 * @example /app/views/scripts/partials/admin/diesTable.phtml
 *
 */
class Pas_View_Helper_Validity
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
     * @param  int $valid
     * @return \Pas_View_Helper_Validity
     */
    public function setValid($valid)
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
                $value = 'Valid';
                break;
            default:
                $value = 'Invalid';
                break;
        }
        return $value;
    }
}