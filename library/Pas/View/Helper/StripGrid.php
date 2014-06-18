<?php
/** A view helper to strip spaces and uppercase grids
 * @version 1
 * @since September 28 2011
 * @author Daniel Pett
 * @copyright DEJ PETT
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 *
 */
class Pas_View_Helper_StripGrid extends Zend_View_Helper_Abstract
{

    protected $_grid;

    protected $_remove = array(' ','-','.','/');

    protected $_replace = array('','','','');

    /** Get the grid reference
     *
     * @return string
     */
    public function getGrid() {
        return $this->_grid;
    }

    /** Set the grid reference
     *
     * @param string $grid
     * @return \Pas_View_Helper_StripGrid
     */
    public function setGrid($grid) {
        $this->_grid = $grid;
        return $this;
    }

    /** the magic method
     *
     * @return string
     */
    public function __toString() {
        return $this->clean();
    }

    /** The cleaner
     *
     * @return string
     */
    public function clean() {
	$gridRef = str_replace($this->_remove, $this->_replace, $this->getGrid());
	$cleaned = strtoupper($gridRef);
	return $cleaned;
    }

    /** the function
     *
     * @return \Pas_View_Helper_StripGrid
     */
    public function stripGrid(){
	return $this;
    }

}