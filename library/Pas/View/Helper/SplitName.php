<?php
/** A view helper to split names and retrieve first name
 * @copyright Daniel Pett <dpett at britishmuseum.org>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @since 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http:// GNU
 */
class Pas_View_Helper_SplitName extends Zend_View_Helper_Abstract
{
    /** The name to explode
     * @access protected
     * @var string
     */
    protected $_name = 'Han Solo';

    /** Get the name to split
     * @access public
     * @return string
     */
    public function getName(){
        return $this->_name;
    }

    /** Set the name to split
     * @access public
     * @param  string $name
     * @return \Pas_View_Helper_SplitName
     */
    public function setName($name){
        $this->_name = $name;

        return $this;
    }

    /** Parent function to split
     * @access public
     * @return \Pas_View_Helper_SplitName
     */
    public function splitName(){
        return $this;
    }

    /** get the first name
     * @access public
     * @return string
     */
    public function firstName(){
        list($first, $last) = explode(' ', $this->getName());
        return $first;
    }

    /** The magic function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->firstName();
    }
}
