<?php 
/**
 * A view helper for displaying a short human readable date
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_NiceShortDate extends Zend_View_Helper_Abstract {

	/**
	 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
	 *
	 * @param string $date_string Datetime string
	 * @return string Formatted date string
	 * @access public
	 */
    private function fromString($date_string) {
        if (is_integer($date_string) || is_numeric($date_string)) {
            return intval($date_string);
        } else {
            return strtotime($date_string);
        }
    }
    /** Format the date as wanted for this view helper
     * @access public
     * @param string $date_string
     * @return string $ret
     */
	public function niceShortDate($date_string ) {
        $date = $this->fromString($date_string);
        $ret = date('l jS F Y', $date);
        return $ret;
    }
	}