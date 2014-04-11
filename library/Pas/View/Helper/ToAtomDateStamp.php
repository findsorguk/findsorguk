<?php 

/**
 * A view helper for displaying a datestamp as atom date
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 */
class Pas_View_Helper_ToAtomDateStamp extends Zend_View_Helper_Abstract {
	/**
	 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
	 *
	 * @param string $date_string Datetime string
	 * @return string Formatted date string
	 * @access public
	 */
	public function fromString($date_string) {
        if (is_integer($date_string) || is_numeric($date_string)) {
            return intval($date_string);
        } else {
            return strtotime($date_string);
        }
	}
    
	/** Format the date as ATOM and return 
	 * 
	 * @param string $date_string
	 */
	
	public function toatomdatestamp($date_string) {
        $date = $this->fromString($date_string);
        $ret = date('Y-m-d\T', $date);
        return $ret;
	}	

}
