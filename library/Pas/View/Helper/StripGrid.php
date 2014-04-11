<?php 
/** A view helper to strip spaces and uppercase grids
 * @version 1
 * @since September 28 2011
 * @author Daniel Pett
 * @copyright DEJ PETT
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 *
 */
class Pas_View_Helper_StripGrid
	extends Zend_View_Helper_Abstract {
	
	/** Strip the grid reference of bad characters
	 * @param string $string
	 *  
	 */
	public function StripGrid($string=""){
	$stripOut = array(' ','-','.','/');
	$gridRef = str_replace($stripOut, '', $string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
	}

}