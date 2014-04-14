<?php
/** Controller for displaying advice pages for the conservation notes module.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Conservation_AdviceController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/
	public function init() {
		$this->_helper->acl->allow('public',null);
	}
	/** Set up each page
	*/
	public function indexAction() {
		if($this->_getParam('slug',false)){
 			$content = new Content();
			$this->view->contents = $content->getContent('conservation',$this->_getParam('slug'));
		} else {
			throw new Pas_Exception_Param('That page is not found.');
		}
	}

}
