<?php
class Pas_View_Helper_ExpiredOrLive extends Zend_View_Helper_Abstract
{

public function expiredorlive($date) {
if($date <= Zend_Date::now()->toString('yyyy-MM-dd')) {
$class = 0;
} else {
$class = 1;
}
return $class;
}
}