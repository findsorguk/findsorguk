<?php
/** Controller for displaying Medieval index pages
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_IndexController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
    }
	/** Setup the index page with examples and front blurb
	*/	
	public function indexAction() {
	$content = new Content();
	$this->view->content =  $content->getFrontContent('medievalcoins');
	}
}
