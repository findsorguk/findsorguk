 <?php

class Zend_View_Helper_Pluralcoins
{
  public function Pluralcoins($string="", $none="no coins", $singular="1 coin", $plural="coins" )
     {
        if ($string == 0) {
           return $none;
        }
		if ($string == 1)
		{
		return $singular;
		}
       return $string . ' ' . $plural;
    }
 }
 ?>