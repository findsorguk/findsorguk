<?php
class Pas_View_Helper_PreferNarrow extends Zend_View_Helper_Abstract
{
function preferNarrow($term)
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