<?php
/**
 * A view helper for rendering completeness of object
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->completeness()->setInt(1);
 * ?>
 * </code>
 * @category   Pas
 * @package View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Validate_Int
 */
class Pas_View_Helper_Completeness extends Zend_View_Helper_Abstract
{
    /** The integer value to lookup
     * @access protected
     * @var int
     */
    protected $_int;

   
    /** Get the id to query
     * @access public
     * @return int
     */
    public function getInt() {
        return $this->_int;
    }


    /** Set the ID to query
     * @access public
     * @param  int                           $int
     * @return \Pas_View_Helper_Completeness
     */
    public function setInt($int) {
        $this->_int = $int;
        return $this;
    }

    /** The completeness function
     * @return \Pas_View_Helper_Completeness
     */
    public function completeness() {
        return $this;
    }

    /** Generate the html
     * pub  
     * @return string
     */
    public function html() {
        switch ($this->getInt()) {
            case '1':
                $comp = 'Fragment';
                break;
            case '2':
                $comp = 'Incomplete';
                break;
            case '3':
                $comp = 'Uncertain';
                break;
            case '4':
                $comp = 'Complete';
                break;
            default:
                $comp = 'Invalid completeness specified';
                break;
        }
        return $comp;
    }


    /** Magic method to return the string
     *
     * @return function
     */
    public function __toString() {
        return $this->html();
    }
}
