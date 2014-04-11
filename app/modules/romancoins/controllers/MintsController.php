<?php
/** Controller for displaying Roman index pages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_MintsController extends Pas_Controller_Action_Admin {

	protected $_mints;
	
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('mint',$contexts)
		->initContext();
	$this->_mints = new Romanmints();
    }
	/** Set up the index action
	* 
	*/	
	public function indexAction() {
	$this->view->rommints = $this->_mints->getRomanMintsList();
	}
	/** Set up the mint action
	* @todo move the config and key to view
	*/	
	public function mintAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$this->view->rommints = $this->_mints->getMintDetails($id);
	$actives = new Rulers();

	$this->view->actives = $actives->getRomanMintRulerList($id);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}