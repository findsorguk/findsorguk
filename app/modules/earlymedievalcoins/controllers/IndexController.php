<?php
/** Controller for displaying Early Medieval coin index page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_IndexController extends Pas_Controller_Action_Admin {
	
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
    }
	/** Set up index page
	*/ 
	public  function indexAction() {
		$content = new Content();
		$this->view->content =  $content->getFrontContent('earlymedievalcoins');
    }
}