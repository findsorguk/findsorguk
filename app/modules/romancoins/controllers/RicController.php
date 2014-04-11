<?php
/** Controller for displaying Roman Imperial Coinage
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Romancoins_RicController extends Pas_Controller_Action_Admin {
	
	
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(NULL);
	}
	/** Set up the index page
	*/	
	public function indexAction() {
	}

}