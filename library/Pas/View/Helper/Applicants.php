<?php
/**
 * A view helper for returning the number of applicants that have applied for 
 * higher level status 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Applicants extends Zend_View_Helper_Abstract {

	public function _getUsers() {
	$users = new Users();
	$data = $users->getNewHigherLevelRequests();
	return $data;
	}

	public function buildHtml() {
	$url = $this->view->url(array(
	'module' => 'admin',
	'controller' => 'users',
	'action' => 'upgrades'
	),NULL,true);
	$data = $this->_getUsers();
	if($data){
	$html = '';
	$html .= '<li class="purple">';
	$html .= '<a href="';
	$html .= $url;
	$html .= '" title="View upgrade requests">';
	$html .= $data['0']['applicants'];
	$html .= ' applicants waiting</a></li>';
	return $html;
	} else {
	return false;
	}
	}
	
	public function Applicants(){
	return $this->buildHtml();
	}
}
