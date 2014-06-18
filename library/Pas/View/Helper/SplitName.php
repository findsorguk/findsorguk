<?php
/** A view helper to split names and retrieve first name
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @since 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http:// GNU
 */
class Pas_View_Helper_SplitName extends Zend_View_Helper_Abstract
{
    /** The name to explode
     *
     * @var string
     */
    protected $_name = 'Daniel Pett';

    /** Get the name to split
     *
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /** Set the name to split
     *
     * @param string $name
     * @return \Pas_View_Helper_SplitName
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /** Parent function to split
     *
     * @return \Pas_View_Helper_SplitName
     */
    public function splitName() {
        return $this;
    }

    /** get the first name
     *
     * @return string
     */
    public function firstName() {
        list($first, $last) = explode(' ', $this->getName());
	return $first;
    }

    /** The magic function
     *
     * @return string
     */
    public function __toString() {
        return $this->firstName();
    }
}