<?php 
/** Controller for displaying Roman index pages
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses RomanMints
 * @uses Pas_Exception_Param
 * @uses Rulers
 */
class RomanCoins_MintsController extends Pas_Controller_Action_Admin {

    /** The mints model
     * @access protected
     * @var \Model
     */
    protected $_mints;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $contexts = array('xml','json');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$contexts)
                ->addActionContext('mint',$contexts)
                ->initContext();
        $this->_mints = new RomanMints();
    }
    /** Set up the index action
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->rommints = $this->_mints->getRomanMintsList();
    }
    /** Set up the mint action
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function mintAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
            $this->view->rommints = $this->_mints->getMintDetails($id);
            $actives = new Rulers();
            $this->view->actives = $actives->getRomanMintRulerList($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}