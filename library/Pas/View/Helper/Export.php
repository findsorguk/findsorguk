<?php
/**
 * A view helper for displaying export links for PAS data
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_Export extends Zend_View_Helper_Abstract {

	/** Export the data and give the correct params for the url
	 * @todo perhaps use route assemble instead?
	 * @param array $params
	 */
	public function Export($params) {
	unset($params['controller']);
	unset($params['module']);
	unset($params['action']);
	unset($params['submit']);
	$where = array();
	foreach($params as $key => $value){
	if(!is_null($value)){
	$where[] = $key . '/' . urlencode($value);
	}
	}
   	$whereString = implode('/', $where);
	$query = $whereString;
	$mapUrl = $this->view->url(array(
	'module' => 'database',
	'controller' => 'search',
	'action' => 'map'
	),null,true) . '/' . $query;
	$map = '<a href="' . $mapUrl . '">Map results</a>';
	$exportformats = '<p>' . $map;
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$exportformats .= ' | <a  href="' . $this->view->url(array(
	'module' => 'database',
	'controller' => 'search',
	'action' => 'save'
	),null,true) . '" title="Save this search">Save this search for later</a> | <a href="' 
	. $this->view->url(array(
	'module' => 'database',
	'controller' => 'search',
	'action' => 'email'
	),null,true) . '" title="Email this search">Email this search</a>';
	}
	$exportformats .= '</p>';
	echo $exportformats;
}
}