<?php
/** Controller for Iron Age period's mint listing page
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Mints
 * @uses Pas_Exception_Param
*/
class IronAgeCoins_MintsController extends Pas_Controller_Action_Admin {

    /** The mints model
     * @access protected
     * @var \Mints
     */
    protected $_mints;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('mint', array('xml','json'))
                ->initContext();
        $this->_mints = new Mints();
    }

    /** Internal period ID number
     * @access protected
     * @var integer
     */
    protected $_period = 16;

    /** Iron Age mints listing pages
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->mints = $this->_mints->listIronAgeMints();
    }

    /** Iron Age individual mint page
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function mintAction() {
        if($this->getParam('id',false)){
            $this->view->id = $this->getParam('id');
            $this->view->mints = $this->_mints->getIronAgeMint((int)$this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}