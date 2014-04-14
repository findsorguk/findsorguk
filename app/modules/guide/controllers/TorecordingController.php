<?php
/** Finds recording guide page controller
 * @category Pas
 * @package Pas_Controller
 * @subpackage Action
 * @version 1
 * @since September 29 2011
 * @filesource /app/modules/guide/controllers/IndexController.php
 * @license GNU
 * @copyright DEJ PETT
 * @author Daniel Pett
 */

class Guide_TorecordingController extends Pas_Controller_Action_Admin {

	/** Initiate the ACL
	 * 
	 */
	public function init() {
	$this->_helper->_acl->allow('public',null);	
	}
	/** The default action - show the home page
	 */
	public function indexAction() {
	if($this->_getParam('slug',0)){	
	$content = new Content();
	$this->view->content = $content->getContent('frg', $this->_getParam('slug'));
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}

