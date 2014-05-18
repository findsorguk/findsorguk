<?php
/**  A view helper to return correct controller for period
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license GNU
 * @version 1
 * @since 18/5/2014
 * @copyright (c) Daniel Pett, <dpett@britishmuseum.org>
 * @todo eradicate the need for this.
 * @category Pas
 * @package Pas_View_Helper
 */
class Pas_View_Helper_DenomController extends Zend_View_Helper_Abstract
{

    /** Period to query
     * @access protected
     * @var string
     */
    protected $_period;

    /** Get the period to query
     * @access public
     * @return string
     */
    public function getPeriod() {
        return $this->_period;
    }

    /** Set the period to query
     * @access public
     * @param string $period
     * @return \Pas_View_Helper_DenomController
     */
    public function setPeriod( string $period) {
        $this->_period = $period;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_DenomController
     */
    public function denomController() {
        return $this;
    }

    /** Switch to get the controller
     * @access public
     * @return string|boolean
     */
    public function determine() {
        switch ($this->getPeriod()) {
            case 'ROMAN':
                $ctrllr = 'romancoins';
                break;
            case 'IRON AGE':
                $ctrllr = 'ironagecoins';
                break;
            case 'MEDIEVAL':
                $ctrllr = 'medievalcoins';
                break;
            case 'EARLY MEDIEVAL':
                $ctrllr = 'earlymedievalcoins';
                break;
            case 'POST MEDIEVAL':
                $ctrllr = 'postmedievalcoins';
                break;
            case 'GREEK AND ROMAN PROVINCIAL':
                $ctrllr = 'greekromancoins';
                break;
            case 'BYZANTINE':
                $ctrllr = 'byzantinecoins';
                break;
            default:
                return false;
	}
	return $ctrllr;
    }

    /** To String method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->determine();
    }
}
