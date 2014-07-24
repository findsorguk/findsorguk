<?php
/** Controller for displaying Medieval mint pages
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Mints
 * @uses Rulers
 * @uses Pas_Exception_Param
 * 
 */
class MedievalCoins_MintsController extends Pas_Controller_Action_Admin {
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('mint', array('xml','json'))
                ->initContext();
        $this->_mints = new Mints();
}
    /** Internal period ID page
     * @access protected
     * @var integer
     */
    protected $_period = 29;
    
    /** Index page for Medieval mints
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->mints = $this->_mints->getListMints($this->_period);
    }
    /** Individual mint page
    */	
    public function mintAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
            $this->view->mints = $this->_mints->getMintDetails($id);
            $actives = new Rulers();
            $this->view->actives = $actives->getMedievalMintRulerList($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}