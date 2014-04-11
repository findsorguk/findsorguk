<?php
class Zend_View_Helper_Fullname extends Zend_View_Helper_Abstract

{

function Fullname()
{
$auth = Zend_Auth::getInstance();
$user = $auth->getIdentity();

if($auth->hasIdentity())
{


$fullname = $this->view->escape(ucfirst($user->fullname));


return $fullname;
}
}


}