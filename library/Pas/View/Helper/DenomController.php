<?php 
 class Pas_View_Helper_DenomController extends Zend_View_Helper_Abstract
 {
public function DenomController($period)
     {
        
        switch ($period) {
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
			break;
	}		
	
	return $ctrllr;
 
}

 }
