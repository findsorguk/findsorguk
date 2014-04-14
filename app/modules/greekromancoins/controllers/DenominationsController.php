<?php
/** Controller for displaying denominations from the Greek and Roman provincial world
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanCoins_DenominationsController extends Pas_Controller_Action_Admin {
	
	protected $_denominations;
	
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('denomination', array('xml','json'))
		->initContext();
	$this->_denominations = new Denominations();
    }
    /** Internal period number
    */ 
    protected $_period = 66;

    /** Set up the index display page
    */ 
    public function indexAction() {
    $this->view->denominations = $this->_denominations->getDenominations($this->_period,$this->_getParam('page'));
    
    }
    
	/** Display individual denomination
	*/     
    public function denominationAction() {
	if($this->_getParam('id',false)){
	$this->view->denoms = $this->_denominations->getDenom((int)$this->_getParam('id'),(int)$this->_period);
    } else {
	throw new Pas_Exception_Param($this->_missingParameter);		
	}
	}
	
}