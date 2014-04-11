 <?php

class Pas_View_Helper_Adminperiod extends Zend_View_Helper_Abstract
{
  public function adminperiod($period)
     {
        if ($period == 21) {
           $periodName =  'Roman';
        }
		if ($period == 47)
		{
		$periodName = 'Early Medieval';
		}
		if ($period == 16)
		{
		$periodName = 'Iron Age';
		}
		if ($period == 29)
		{
		$periodName = 'Medieval';
		}
		if ($period == 36)
		{
		$periodName = 'Post Medieval';
		}
		if ($period == 66)
		{
		$periodName = 'Greek and Roman Provincial';
		}
		if ($period == 67)
		{
		$periodName = 'Byzantine';
		}
		return $periodName;
     }
 }
 ?>