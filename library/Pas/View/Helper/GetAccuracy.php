<?php
/** This view helper gets the accuracy of a grid reference
 * @todo phase this out as it won't be needed shortly
 * @author Daniel Pett
 * @copyright DEJ Pett
 * @license GNU
 * @version 1
 * @since 28 September 2011
 * @category Pas
 * @package Pas_View_Helper
 */

class Pas_View_Helper_GetAccuracy
	extends Zend_View_Helper_Abstract {
	/** Strip out the NGR bad characters
	 * 
	 * @param string $string
	 */		
	private function stripgrid($string=""){
	$stripOut = array(" ","-",'/',".");
	$gridRef = str_replace($stripOut,"",$string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
	}

	/** Get accuracy of the grid ref
	 * 
	 * @param string $gridref
	 * @param int $clean
	 */
	public function GetAccuracy($gridref,$clean=  1){

	if ($clean == 1){$gridref = $this->stripgrid($gridref);}
	$coordCount = strlen($gridref)-2; //count length and strip off fist two characters

	switch ($coordCount) {
		case 0:
			$acc = 100000;
			break;
		case 2:
			$acc = 10000;
			break;
		case 4:
			$acc = 1000;
			break; 
		case 6:
			$acc = 100;
			break;
		case 8:
			$acc = 10;
			break;
		case 10:
			$acc = 1;
			break;
		case 12:
			$acc = 0.1;
			break;
		case 14:
			$acc = 0.01;
			break;
		default:
			return false;
			break;
	}		
	
	$gridAcc = $acc;
	return $acc;	
	}

}