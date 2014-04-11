<?php
/**
 * A basic view helper for displaying ip address
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_IpAddress extends Zend_View_Helper_Abstract {
	
	/**
 	* Check for the certainty
 	* @param $int The certainty lookup number 
 	*/
	public function ipAddress(){
	return Zend_Controller_Front::getInstance()->getRequest()->getClientIp();
	}
}