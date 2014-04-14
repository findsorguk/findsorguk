<?php
/** Controller for displaying articles from the Medieval coin guide
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Medievalcoins_ArticlesController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init(){
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	}
	/** Setup the front article pages
	*/	
	public function indexAction() {
	$content = new Content();
	$this->view->contents = $content->getSectionContents('medievalcoins');
	}
	/** Setup an individual page
	*/	
	public function pageAction() {
 	$content = new Content();
	$this->view->contents = $content->getContent('medievalcoins', (string)$this->_getParam('slug'));
    }
}
