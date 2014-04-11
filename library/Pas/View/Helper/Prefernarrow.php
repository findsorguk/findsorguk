<?php
class Pas_View_Helper_Prefernarrow extends Zend_View_Helper_Abstract
{
function prefernarrow($term)
{
switch($term)
{
	case 'P':
	$p = 'Preferred term';
	break;
	case 'N':
	$p = 'Narrow term';
	break;
	default:
	$p = "WTF";
	break;
}
return $p;
}

}