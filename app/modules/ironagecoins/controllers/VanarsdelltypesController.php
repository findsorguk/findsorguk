<?php
/** Controller for Iron Age Van Ardsell types
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_VanarsdelltypesController extends Pas_Controller_Action_Admin {

    /** Setup the contexts by action and the ACL.
     */
    public function init() {
    $this->_types = new VanArsdellTypes();
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
    }

    /** Setup the index page for Van Arsdell Types
     */
    public function indexAction() {
    $this->view->va = $this->_types->getVaTypes($this->_getAllParams());
    }

    /** Get details for a specific type
     *
     */
    public function typeAction(){
    $this->view->type = $this->_types->fetchRow($this->_types->select()->where('type = ?',$this->_getParam('id')));
    }
}