<?php
/** Controller for displaying Post medieval articles data
* 
* @category   Pas
* @package Pas_Controller_Action
* @subpackage Admin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Postmedievalcoins_ArticlesController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	}
	/** Set up the article index page
	*/		
	public function indexAction() {
	$content = new Content();
	$this->view->contents = $content->getSectionContents('postmedievalcoins');
	}
	/** Individual page details
	*/	
	public function pageAction() {
 	$content = new Content();
	$this->view->contents = $content->getContent('postmedievalcoins', $this->_getParam('slug'));
    }
}