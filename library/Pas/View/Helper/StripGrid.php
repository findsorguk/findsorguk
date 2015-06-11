<?php
/** A view helper to strip spaces and turn grid references into uppercase
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * echo $this->stripGrid()->setGrid($this->gridref);
 * ?>
 * </code>
 * @version 1
 * @since September 28 2011
 * @author Daniel Pett
 * @copyright DEJ PETT
 * @category Pas
 * @package View
 * @subpackage Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/views/scripts/partials/database/geodata/findSpot.phtml
 */
class Pas_View_Helper_StripGrid extends Zend_View_Helper_Abstract
{
    /** The grid reference to use
     * @access protected
     * @var string The grid reference
     */
    protected $_grid;

    /** The characters to remove
     * @access protected
     * @var array
     */
    protected $_remove = array(' ', '-', '.', '/');

    /** The replacement characters
     * @var array
     * @access protected
     */
    protected $_replace = array('', '', '', '');

    /** Get the grid reference
     * @access public
     * @return string
     */
    public function getGrid()
    {
        return $this->_grid;
    }

    /** Set the grid reference
     * @access public
     * @param  string $grid
     * @return \Pas_View_Helper_StripGrid
     */
    public function setGrid($grid)
    {
        $this->_grid = $grid;
        return $this;
    }

    /** the magic method
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->clean();
    }

    /** The cleaner
     * @access public
     * @return string
     */
    public function clean()
    {
        $gridRef = str_replace($this->_remove, $this->_replace, $this->getGrid());
        $cleaned = strtoupper($gridRef);
        return $cleaned;
    }

    /** The function to strip grids
     * @access public
     * @return \Pas_View_Helper_StripGrid
     */
    public function stripGrid()
    {
        return $this;
    }
}