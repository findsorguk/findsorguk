<?php
class Pas_View_Helper_Allowedtest extends Zend_View_Helper_Abstract
{
public function allowedtest($string)
{
$auth = Zend_Auth::getInstance();
if($auth->hasIdentity())
{
$higherLevel = array('admin'); 
$user = $auth->getIdentity();
$role = $user->role;
if(in_array($role,$higherLevel))
{
echo $string;
}
}
}


}