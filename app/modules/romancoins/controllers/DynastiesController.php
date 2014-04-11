<?php
/** Controller for displaying Roman dynasties
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_DynastiesController extends Pas_Controller_Action_Admin {
	
	protected $_contexts = array('xml', 'json');
	
	protected $_dynasties;
	
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$this->_contexts)
		->addActionContext('dynasty',$this->_contexts)
		->initContext();
	$this->_dynasties = new Dynasties();
	}

	/** Set up the index pages
	*/
	public function indexAction() {
	$this->view->dynasties = $this->_dynasties->getDynastyList();
	}
	/** Set up the individual dynasty
	*/
	public function dynastyAction() {
	if($this->_getParam('id',false)) {
	$this->view->dynasties = $this->_dynasties->getDynasty($this->_getParam('id'));
	$emperors = new Emperors();
	$this->view->emperors = $emperors->getEmperorsDynasty($this->_getParam('id'));
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}