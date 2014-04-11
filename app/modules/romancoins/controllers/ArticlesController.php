<?php
/** Controller for displaying Roman articles within the coin guide
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Romancoins_ArticlesController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	}
	/** Set up the index page
	*/	
	public function indexAction() {
	$content = new Content();
	$this->view->contents = $content->getSectionContents('romancoins');
	}
	/** Set up individual page
	*/	
	public function pageAction() {
 	$content = new Content();
	$this->view->contents = $content->getContent('romancoins', $this->_getParam('slug'));
    }
}