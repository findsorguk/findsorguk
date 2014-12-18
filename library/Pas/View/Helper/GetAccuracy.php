<?php
/** This view helper gets the accuracy of a grid reference
 *
 * An example use:
 *
 * <code>
 * <?php
 * echo $this->getAccuracy()->setGridRef('TQ1234');
 * ?>
 * </code>
 *
 *
 * @todo phase this out as it won't be needed shortly
 * @author Daniel Pett
 * @copyright DEJ Pett
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 28 September 2011
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/views/scripts/partials/database/findSpot.phtml
 */

class Pas_View_Helper_GetAccuracy extends Zend_View_Helper_Abstract {

    /** The grid ref to clean
     * @access protected
     * @var string
     */
    protected $_gridRef;

    /** The function to grid ref
     * @access public
     * @return type
     */
    public function getGridRef() {
        return $this->_gridRef;
    }

    /** Set the grid ref
     * @access public
     * @param type $gridRef
     * @return \Pas_View_Helper_GetAccuracy
     */
    public function setGridRef($gridRef) {
        $this->_gridRef = $gridRef;
        return $this;
    }

    /** Strip out the NGR bad characters
     * @access public
     * @param string $string
     */
    public function stripgrid( $string ) {
        $stripOut = array(" ","-",'/',".");
        $gridRef = str_replace($stripOut,"",$string);
        $clean = strtoupper($gridRef);
        return $clean;
    }

    public function getAccuracy() {
        return $this;
    }
    /** Get accuracy of the grid ref
     *
     * @param string $gridref
     * @param int    $clean
     */
    public function __toString() {
        $gridref = $this->stripgrid($this->getGridRef());
        $coordCount = strlen($gridref) - 2; //count length and strip off fist two characters
        switch ($coordCount) {
            case 0:
                $acc = 100000;
                break;
            case 2:
                $acc = 10000;
                break;
            case 4:
                $acc = 1000;
                break;
            case 6:
                $acc = 100;
                break;
            case 8:
                $acc = 10;
                break;
            case 10:
                $acc = 1;
                break;
            case 12:
                $acc = 0.1;
                break;
            case 14:
                $acc = 0.01;
                break;
            default:
                $acc = 'Not in range!';
                break;
        }
        return (string)$acc;
    }

}