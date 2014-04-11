<?php
/**
 * A view helper for displaying sub periods from lookup number
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 */

class Pas_View_Helper_Subperiod extends Zend_View_Helper_Abstract {

	/** Return the correct sub period
	 * @access public
	 * @param integer $period
	 * @return string
	 */
	public function subperiod($period){
	switch ($period) {
		case 1:
			$cert = 'Early';
			break;
		case 2:
			$cert = 'Middle';
			break;
		case 3:
			$cert = 'Late';
			break; 
		default:
			return false;
			break;
	}
	return $cert;
	}
}