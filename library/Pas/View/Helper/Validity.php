<?php 
class Pas_View_Helper_Validity extends Zend_View_Helper_Abstract
{

public function validity($valid = NULL)
{
switch ($valid) {
		case 1:
			$v = 'Valid';
			break;
		default:
			$v = 'Invalid';
			break;
	}		
	
	return $v;
 
}

}
