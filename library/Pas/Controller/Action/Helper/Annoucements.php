<?php
 /**
 * ACL integration
 *
 * Places_Controller_Action_Helper_Acl provides ACL support to a 
 * controller.
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @package    Controller
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2007,2008 Rob Allen
 * @license    http://framework.zend.com/license/new-bsd  New BSD License
 */
class Pas_Controller_Action_Helper_Annoucements extends Zend_Controller_Action_Helper_Abstract {

	public function direct()
	{
		$announcements = new Quotes();
		return $announcements->getAnnoucements();
	}
}