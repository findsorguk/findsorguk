<?php
/** Controller for displaying Medieval denominations
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {
	
	protected $_denominations;
	
	/** Setup the contexts by action and the ACL.
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('denomination', array('xml','json'))
		->initContext();
	$this->_denominations = new Denominations();
    }
    
	/** Setup the contexts by action and the ACL.
	*/	
    protected $_period = 29;

	/** Setup index page for Medieval denominations
	*/	
	public function indexAction() {
	$this->view->denominations = $this->_denominations->getDenominations((int)$this->_period, (int)$this->_getParam('page'));
	}
	
	/** Setup the denomination details
	*/	
	public function denominationAction() {
	if($this->_getParam('id',false)){
	$id = (int)$this->_getParam('id');
	$this->view->denoms = $this->_denominations->getDenom($id,$this->_period);
	$this->view->rulers = $this->_denominations->getRulerDenomination($id);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
