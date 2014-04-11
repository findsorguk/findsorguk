<?php


class Pas_View_helper_PeopleToolbox extends Zend_View_Helper_Abstract
{
protected $_allowed = array('fa','flos','admin','treasure');

public function peopleToolbox($id = NULL)
{


$auth = Zend_Registry::get('auth');
if($auth->hasIdentity())
{
$user = $auth->getIdentity();
$role = $user->role;
$institution = $user->institution;
{
if(in_array($role,$this->_allowed))
{
echo '<div id="toolBox"><p>';

echo '<a class="btn btn-large btn-primary" href="'
. $this->view->url(array('module' => 'database','controller'=>'people','action'=>'add'),NULL, true)
.'">Add new person to database</a>';
echo'</p></div>';

}
}
}
}

}
