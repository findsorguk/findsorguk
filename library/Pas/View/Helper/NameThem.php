<?php

/**
 * A view helper for displaying name or the Latin phrase
 *
 * A bit of a pointless view helper
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->nameThem()->setName('Daniel Pett');
 * ?>
 * </code>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_NameThem extends Zend_View_Helper_Abstract
{
    /** The name to present
     * @access protected
     * @var string
     */
    protected $_name;

    /** Get the name to return
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /** Set the name
     * @access public
     * @param string $name
     * @return \Pas_View_Helper_NameThem
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        $html = '';
        if (!$this->getName()) {
            $html = '<em>Nemo hic adest illius nominis</em>';
        } else {
            $html = $this->getName();
        }
        return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Namethem
     */
    public function nameThem()
    {
        return $this;
    }
}
