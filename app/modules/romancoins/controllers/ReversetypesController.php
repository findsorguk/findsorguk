<?php
/** Controller for displaying Roman reverse types
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_ReverseTypesController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('reversetype',$contexts)
		->initContext();
    }
	/** Set up the index page
	*/	
	public function indexAction() {
	$reverses = new Revtypes();
	$this->view->reverses = $reverses->getReverseTypeList(1);
	$uncommonreverses = new Revtypes();
	$this->view->uncommonreverses = $uncommonreverses->getReverseTypeList(2);
	}
	/** Set up the individual reverse type
	*/		
	public function typeAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$reverses = new Revtypes();
	$this->view->reverses = $reverses->getReverseTypesDetails($id);
	$emps = new Emperors();
	$this->view->emps = $emps->getEmperorRevTypes($id);
	$mints = new Mints();
	$this->view->mints = $mints->getMintReverseType($id);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}
