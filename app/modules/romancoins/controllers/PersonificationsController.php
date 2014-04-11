<?php
/** Controller for displaying Roman personifications lists
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_PersonificationsController extends Pas_Controller_Action_Admin {
	
	protected $_reverses;
	
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('named',$contexts)
		->initContext();
	$this->_reverses = new Reverses();
    }

        /** Set up the index page
	*/
	public function indexAction() {
	$this->view->gods 	 =  $this->_reverses->getPersonifications('god');
	$this->view->virtues =  $this->_reverses->getPersonifications('virtue');
	$this->view->symbols =  $this->_reverses->getPersonifications('symbol');
	}
	/** Set up the individual named personification
	*/
	public function namedAction() {
	if($this->_getParam('as',false)) {
		$this->view->details =  $this->_reverses->getPersonification($this->_getParam('as'));
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
