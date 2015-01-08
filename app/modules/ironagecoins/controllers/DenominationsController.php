<?php
/** Controller for displaying denominations from the Iron Age period
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Denominations
 * @uses Geography
 * @uses Pas_Exception_Param
*/
class IronAgeCoins_DenominationsController extends Pas_Controller_Action_Admin {

    /** The denominations model
     * @access protected
     * @var \Denominations
     */
    protected $_denominations;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('denomination', array('xml','json'))
                ->initContext();
        $this->_denominations = new Denominations();
    }
    /** Internal period number
     * @access protected
     * @var integer
     */
    protected $_period = 16;

    /** Set up index page for Iron Age denominations
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->denoms = $this->_denominations->getIronAgeDenoms();
    }

    /** An individual denomination's entry details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function denominationAction() {
        if($this->getParam('id', false)){
            $id = $this->getParam('id');
            $this->view->id = $id;
            $this->view->denoms = $this->_denominations->getDenom($id, $this->_period);
            $regions = new Geography();
            $this->view->regions = $regions->getIronAgeDenomGeog($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}