<?php
/** Controller for Iron Age tribes
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_TribesController extends Pas_Controller_Action_Admin {

    /** Setup the contexts by action and the ACL.
    */
    public function init() {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('tribe', array('xml','json'))
            ->initContext();
    $this->_tribes = new Tribes();
    }

    /** Setup the index page for Iron Age tribes
     *
     */
    public function indexAction() {

    $this->view->tribes = $this->_tribes->getTribesList();
    }
    /** Setup individual tribe page
    */
    public function tribeAction() {
    if($this->_getParam('id',false)) {
    $id = (int)$this->_getParam('id');
    $this->view->id = $id;
    $this->view->tribes = $this->_tribes->getTribe($id);
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}