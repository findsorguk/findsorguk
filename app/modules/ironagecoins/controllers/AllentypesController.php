<?php
/** Controller for displaying Iron Age coins Allen Types
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_AllentypesController extends Pas_Controller_Action_Admin {
	
	protected $_allenTypes;
	
    /** Setup the contexts by action and the ACL.
    */
    public function init() {
    $this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
  		->addActionContext('type', array('xml','json'))
		->initContext();
	$this->_allenTypes = new AllenTypes();
    }
    
    /** Create index pages for Allen Types available to the user
    */
    public function indexAction() {
    $this->view->allens = $this->_allenTypes->getAllenTypes($this->_getAllParams());
    
    }

    public function typeAction(){
    $types = new AllenTypes();
    $this->view->type = $this->_allenTypes->fetchRow($this->_allenTypes->select()->where('type = ?', 
            $this->_getParam('id')));
    }
}
