<?php
/** Controller for displaying denominations from the Iron Age period
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_DenominationsController extends Pas_Controller_Action_Admin {

    protected $_denominations;

    /** Setup the contexts by action and the ACL.
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
    */
    protected $_period = 16;

    /** Set up index page for Iron Age denominations
    */
    public function indexAction() {
    $this->view->denoms = $this->_denominations->getIronAgeDenoms();
    }

    /** An individual denomination's entry details
     *
     */
    public function denominationAction() {
    if($this->_getParam('id',false)){
    $id = $this->_getParam('id');
    $this->view->id = $id;
    $this->view->denoms = $this->_denominations->getDenom($id, $this->_period);
    $regions = new Geography();
    $this->view->regions = $regions->getIronAgeDenomGeog($id);
    } else {
    	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}