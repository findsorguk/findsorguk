<?php 
/** A view helper to translate integers to month
 * 
 * @author dpett
 *
 */
class Pas_View_Helper_Nicemonth extends Zend_View_Helper_Abstract {
   
	public function nicemonth($date) {
       switch ($date) {
		case 01:
			$month = 'January';
			break;
		case 02:
			$month = 'February';
			break;
		case 03:
			$month = 'March';
			break; 
		case 04:
			$month = 'April';
			break; 
		case 05:
			$month = 'May';
			break; 
		case 06:
			$month = 'June';
			break;
		case 07:
			$month = 'July';
			break;
		case 8:
			$month = 'August';
			break;
		case 9:
			$month = 'September';
			break;
		case 10:
			$month = 'October';
			break;
		case 11:
			$month = 'November';
			break;
		case 12:
			$month = 'December';
			break;
		default:
			return $month = $date;
			break;
	}		
	return $month; 
    }
	
	}