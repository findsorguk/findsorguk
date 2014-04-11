<?php
/**
 * Display some links if logged in.
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_Loggedinlinks extends Zend_View_Helper_Abstract  {

	/** Check if user logged in and then display the link
	 *
	 */
	public function loggedinlinks() {
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$url =  $this->view->url(array(
	'module' => 'database',
	'controller'=>'artefacts',
	'action'=>'add'), NULL, true);
	$urlstring = '<div id="action"><p><a class="btn btn-large btn-success" href="'
	. $url . '" title="Add a new artefact" accesskey="a">Add new artefact</a></p></div>';
	return $urlstring;
	} else {
	return false;
	}
	}

}