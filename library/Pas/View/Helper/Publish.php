<?php
/**
 * A view helper for rendering publication states
 *
 * An example of use
 * <code>
 * <?php
 * echo $this->publish()->setState(1);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 *
 */

class Pas_View_Helper_Publish extends Zend_View_Helper_Abstract
{

    /** The state of publication to query
     * @access protected
     * @var int
     */
    protected $_state;

    /** Get the publication state
     * @access public
     * @return int
     */
    public function getState() {
        return $this->_state;
    }

    /** Set the state of publication
     * @access public
     * @param int $state
     * @return \Pas_View_Helper_Publish
     */
    public function setState($state) {
        $this->_state = $state;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Publish
     */
    public function publish(){
        return $this;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
       switch ($this->getState()) {
        case 1:
            $status = 'Draft';
            break;
        case 2:
            $status = 'Pending';
            break;
        case 3:
            $status = 'Published';
            break;
        default:
            $status = 'Publication state unknown';
            break;
       }
       return $status;
    }

    }
