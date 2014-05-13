<?php
class Pas_View_Helper_StaffsMenu extends Zend_View_Helper_Abstract
{

function staffsMenu()
{
	$staffs = new Content();
	$staff = $staffs->buildMenu('staffs');
	foreach($staff as $t) {
	echo '<li><a href="';
	echo $this->view->url(array('module' => 'staffshoardsymposium','controller' => 'papers','action' => 'index','slug' => $t['slug']),'staffs',true);
	echo '" title="Read more">';
	echo $t['menuTitle'];
	echo '</a></li>';

	}

}



}