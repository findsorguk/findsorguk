<?php
/** Controller for displaying index page for Greek and Roman provincial world coins
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanCoins_IndexController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init()  {
 	$this->_helper->_acl->allow(null);
    }
    
	/** Internal period number
	*/ 
	protected $_period = '66';
	
	/** Set up the index display pages
	*/    
	public function indexAction()  {
	$content = new Content();
	$this->view->content =  $content->getFrontContent('greekromancoins');    
    }
}
