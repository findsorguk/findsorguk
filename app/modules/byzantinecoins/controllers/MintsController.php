<?php
/** Controller for displaying byzantine mint pages with recent examples
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ByzantineCoins_MintsController extends Pas_Controller_Action_Admin {
    
	protected $_mints;
	
    /** Initialise the ACL and contexts
    */
    public function init()  {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('mint', array('xml','json'))
            ->initContext();
    $this->_mints = new Mints();
    }

	/** Set up the index pages
	*/
    public function indexAction() {
    $this->view->mints = $this->_mints->getMintsByzantineList();
    }
    /** Set up the specific mint page
    */
    public function mintAction() {
    if($this->_getParam('id',false)){
    	$this->view->mints = $this->_mints->getMintDetails($this->_getParam('id'));
    	$this->view->id = $this->_getParam('id');
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}