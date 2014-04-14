<?php
/** Controller for displaying Medieval types pages
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_TypesController extends Pas_Controller_Action_Admin {
	
	protected $_types;
	
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
	$this->_types = new MedievalTypes();
    }
	/** Internal period ID number
	*/	
    protected $_period = 29;

	/** Index page for list of Medieval types
	*/	
    public function indexAction() {
	$this->view->types = $this->_types->getTypesByPeriod((int)$this->_period,(int)$this->_getParam('page'));
	}
	
	/** Medieval type details page
	*/	
	public function typeAction() {
	if($this->_getParam('id',false)){
	$this->view->types = $this->_types->getTypeDetails((int)$this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}