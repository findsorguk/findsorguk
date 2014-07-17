<?php
/** This class strips out spaces from a string.
 * 
 * Primarily used to remove spaces from the grid references provided by FLOs.
 *
 * An example of code use:
 * 
 * <code>
 * <?php
 * $gridref = new Zend_Form_Element_Text('gridref');
 * $gridref->setLabel('Grid reference: ')
 * 	->addValidators(array('NotEmpty','ValidGridRef'))
 * 	->addFilters(array('StripSpaces'));
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Filter
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/forms/FindSpotForm.php
 */
class Pas_Filter_StripSpaces implements Zend_Filter_Interface {

    /** Holds whether to replace all spaces or not.
     * @access protected
     * @var boolean
     */
    protected $_replaceAllSpaces;


    /** Construct the class
     * @access public
     * @throws Zend_Filter_Exception
     * @param bool $replaceAllSpaces
     */
    public function __construct($replaceAllSpaces = true) {
        if (!is_bool($replaceAllSpaces)) {
            throw new Zend_Filter_Exception('Argument must be a bool');
        }
        $this->_replaceAllSpaces = $replaceAllSpaces;
    }

    /** Returns whether to replace all spaces or not
     * @access public
     * @return boolean
     */
    public function getReplaceAllSpaces() {
        return $this->_replaceAllSpaces;
    }

    /** Sets whether to replace all spaces or not
     * @access public
     * @param boolean $replaceAllSpaces
     * @return \Pas_Filter_StripSpaces
     * @throws Zend_Filter_Exception
     */
    public function setReplaceAlLSpaces($replaceAllSpaces) {
        if (!is_bool($replaceAllSpaces)) {
            throw new Zend_Filter_Exception('Argument must be a bool');
        }
        $this->_replaceAllSpaces = $replaceAllSpaces;
        return $this;
    }

    /** Returns a value replacing (all) spaces.
     * @access public
     * @param  string $value
     * @return string
     */
    public function filter($value) {
        if ($this->_replaceAllSpaces) {
            return str_replace(' ', '', $value);
        }
        return preg_replace('/ +/', ' ', $value);
    }
}