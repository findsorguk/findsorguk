<?php 
class Pas_View_Helper_Publish extends Zend_View_Helper_Abstract
{
   
	public function publish($state) {
       switch ($state) {
		case 1:
			$status = 'Draft';
			break;
		case 2:
			$status = 'Pending';
			break;
		case 3:
			$status = 'Published';
			break; 
		
		default:
			return false;
			break;
	}		
	
	return $status; 
    }
	
	}