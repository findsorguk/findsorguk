<?php
/** Controller for getting information on data transfer to HERs
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Research_DatatransferController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
 	$this->_helper->_acl->allow(null);
	}
	
	/** Get data for data transfer index page
	*/ 
	public function indexAction() {
 	$content = new Content();
	$this->view->contents = $content->getFrontContent('datatransfer');
    }
    
    /** Get data for HER page
	*/ 
	public function hersAction() {
	$hers = new Hers();
	$this->view->hers = $hers->getAll($this->_getAllParams());
	}
	
}