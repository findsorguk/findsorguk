<?php
/** Controller for displaying map of flickr photos from the PAS account
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Flickr_MapController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	}
	/** Setup index page
	*/	
	public function indexAction() {
	}
	
}