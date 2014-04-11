<?php
/**
 * A view helper for displaying the current page URL
 * Not sure if this was inspired by a wordpress plugin or not?!
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_CurUrl extends Zend_View_Helper_Abstract  {
	
 	public function CurUrl() {
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
	$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") 
		|| ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
	$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
	$url = ($isHTTPS ? 'https://' : 'http://') . $_SERVER["SERVER_NAME"] . $port 
	. $_SERVER["REQUEST_URI"];
	return $url;
	}
}