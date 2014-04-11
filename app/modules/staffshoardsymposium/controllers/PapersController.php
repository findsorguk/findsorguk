<?php
/** Controller for the Staffordshire symposium paper page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/


class Staffshoardsymposium_PapersController extends Pas_Controller_Action_Admin {
	/**
	 * The default action - show the home page
	 */
	public function init()
	{
	$this->_helper->_acl->allow('public',null);	
	}
	
	public function indexAction() {
	if($this->_getParam('slug',0)){	
		$content = new Content();
		$this->view->content = $content->getContent('staffs', $this->_getParam('slug'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}

