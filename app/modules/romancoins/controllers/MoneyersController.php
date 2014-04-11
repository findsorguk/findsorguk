<?php
/** Controller for displaying Roman republican moneyers
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Romancoins_MoneyersController extends Pas_Controller_Action_Admin {
	
	protected $_moneyers;
	
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(NULL);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);	
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('called',$contexts)
		->initContext();
	$this->_moneyers = new Moneyers();
	}
	/** Set up the index page
	*/	
	public function indexAction() {
	$this->view->moneyers = $this->_moneyers->getValidMoneyers($this->_getAllParams());
	}
	/** Set up the moneyer individual pages
	*/		
	public function calledAction() {
	if($this->_getParam('by',false)){
	$this->view->moneyer = $this->_moneyers->getMoneyer($this->_getParam('by'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}