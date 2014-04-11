<?php
/** Controller for Iron Age period's mack types
* This listing is now pretty much obsolete, but is retained for concordance. 
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_MacktypesController extends Pas_Controller_Action_Admin {
    
	protected $_mackTypes;
	
	/** Set up the ACL and the contexts
	*/    
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
	$this->_mackTypes = new MackTypes();
    }
    
	/** Internal period ID number for the Iron Age
	*/       
	protected $_period = 16;
    
	/** Set up the Mack type index pages
	*/    
	public function indexAction() {
    $this->view->macks = $this->_mackTypes->getMackTypes($this->_getAllParams());
    }
    
 	public function typeAction(){
	$this->view->type = $this->_mackTypes->fetchRow($this->_mackTypes->select()->where('type = ?',urlencode($this->_getParam('id'))));
    }
    
}
