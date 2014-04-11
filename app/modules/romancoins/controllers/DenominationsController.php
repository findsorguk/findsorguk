<?php
/** Controller for displaying Roman denominations
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_DenominationsController extends Pas_Controller_Action_Admin {

	protected $_denominations, $_contexts = array('xml', 'json');
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$this->_contexts)
		->addActionContext('denomination',$this->_contexts)
		->initContext();
	$this->_denominations = new Denominations();
        }
	/** Set up the index page
	*/
	public function indexAction() {
	
	$this->view->denominations = $this->_denominations->getDenByPeriod((int)21);
	}
	/** Set up the individual denominations
	*/
	public function denominationAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$this->view->denoms = $this->_denominations->getDenom($id,(int)21);
	$emps = new Emperors();
	$this->view->emps = $emps->getDenomEmperor($id);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
