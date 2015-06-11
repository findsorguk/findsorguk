<?php

/**
 * A view helper for purifying Html
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->purify()->setValue($string);
 * ?>
 * </code>
 *
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see  Zend_View_Helper_Abstract
 * @uses Pas_Filter_HtmlCleaned
 */
class Pas_View_Helper_Purify extends Zend_View_Helper_Abstract
{

    /** The value to purify
     * @access protected
     * @var string
     */
    protected $_value;

    /** Get the value
     * @access protected
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /** Set the value
     * @access public
     * @param string $value
     * @return \Pas_View_Helper_Purify
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /** The filter object
     * @access protected
     * @var \Pas_Filter_HtmlCleaned
     */
    protected $_filter;

    public function getFilter()
    {
        $this->_filter = new Pas_Filter_HtmlCleaned();
        return $this->_filter;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_Purify
     */
    public function purify()
    {
        return $this;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getFilter()->filter($this->getValue());
    }
}