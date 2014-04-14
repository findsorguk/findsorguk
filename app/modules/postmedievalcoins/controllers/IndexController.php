<?php
/** Controller for displaying Post medieval coins index pages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_IndexController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
    }
	/** Set up the index pages
	*/	
	public function indexAction() {
	$content = new Content();
	$this->view->content =  $content->getFrontContent('postmedievalcoins');
	}

}