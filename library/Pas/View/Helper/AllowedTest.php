<?php
class Pas_View_Helper_AllowedTest extends Zend_View_Helper_Abstract
{
public function allowedTest($string)
{
$auth = Zend_Auth::getInstance();
if ($auth->hasIdentity()) {
$higherLevel = array('admin');
$user = $auth->getIdentity();
$role = $user->role;
if (in_array($role,$higherLevel)) {
echo $string;
}
}
}

}
