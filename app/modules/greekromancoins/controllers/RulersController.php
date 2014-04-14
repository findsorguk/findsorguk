<?php
/** Controller for displaying rulers from the Greek and Roman provincial world
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanCoins_RulersController extends Pas_Controller_Action_Admin {
	
	protected $_rulers;
	
	/** Initialise the ACL and contexts
	*/ 
	public function init(){
 	$this->_helper->_acl->allow(null);
 	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('ruler', array('xml','json'))
             ->initContext();
    $this->_rulers = new Rulers();
    }
	/** Internal period number
	*/ 
	protected $_period = 66;
	/** Set up the index page
	*/ 
    public function indexAction() {
	$this->view->greeks = $this->_rulers->getRulersGreekList($this->_getAllParams());
	}
	
	/** Individual ruler page
	*/ 	
	public function rulerAction() {
	if($this->_getParam('id',false)){
	$this->view->greek= $this->_rulers->getRulerProfile($this->_getParam('id'));
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);		
	}
	}
	
}