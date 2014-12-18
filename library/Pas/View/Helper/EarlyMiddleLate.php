<?php

/** A view helper for displayin the qualifier for Early|Middle|Late period data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->earlyMiddleLate()->setQualifier(1);
 * ?>
 * </code>
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @since 30.1.12
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @copyright Daniel Pett <dpett@britishmuseum.org>
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_View_Helper_EarlyMiddleLate extends Zend_View_Helper_Abstract
{
    /** The qualifier integer
     * @access protected
     * @var int
     */
    protected $_qualifier;

    /** Get the qualifier number
     * @access public
     * @return int
     */
    public function getQualifier()
    {
        return $this->_qualifier;
    }

    /** Set the qualifier
     * @access public
     * @param  int $qualifier
     * @return \Pas_View_Helper_EarlyMiddleLate
     */
    public function setQualifier($qualifier)
    {
        $this->_qualifier = $qualifier;
        return $this;
    }

    /** return the function
     * @access public
     * @return \Pas_View_Helper_EarlyMiddleLate
     */
    public function earlyMiddleLate()
    {
        return $this;
    }

    /** return the string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getSwitch();
    }

    /** Switch for displaying correct period qualifier
     * @access public
     * @param  string $qualifier
     * @return string
     */
    public function getSwitch()
    {
        switch ($this->getQualifier()) {
            case 1:
                $string = 'Early';
                break;
            case 2:
                $string = 'Middle';
                break;
            case 3:
                $string = 'Late';
                break;
            default:
                $string = '';
                break;
        }
        return $string;
    }
}