<?php
/** Controller for displaying index page of the flickr module
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author Daniel Pett
*/
class Flickr_IndexController extends Pas_Controller_Action_Admin {
	
	public function init(){
	$this->_helper->acl->allow('public',null);
	}
	
	/** Display the index page
	*/			
	public function indexAction() {
	}
	
}