<?php
/** Controller for displaying Post medieval mints data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_MintsController extends Pas_Controller_Action_Admin {

    protected  $_mints;

    /** Set up the ACL and contexts
    */
    public function init() {
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->acl->allow('public',null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('mint', array('xml','json'))
            ->initContext();
    $this->_mints = new Mints();
    }
    /** List of mints
    */
    public function indexAction(){
    $this->view->mints = $this->_mints->getListMints(36);
    }
    /** Mint details
    */
    public function mintAction() {
    if($this->_getParam('id',false)) {
    $this->view->mints = $this->_mints->getMintDetails($this->_getParam('id'));
    $actives = new Rulers();
    $this->view->actives = $actives->getMedievalMintRulerList($this->_getParam('id'));
    } else {
    throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}
