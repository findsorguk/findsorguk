<?php
/** Controller for displaying Early Medieval coin denominations
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL and contexts
    */
    public function init() {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('denomination', array('xml','json'))
            ->initContext();
    }

    /** Internal period number for querying the database
    */
    protected $_period = 47;

    /** Set up index page for denominations
    */
    public function indexAction() {
    $denominations = new Denominations();
    $this->view->denominations = $denominations->getDenominations($this->_period,null);

    }

    /** Get details of each individual denomination
    * @param int $id denomination number
    */
    public function denominationAction() {
    if($this->_getParam('id',false)) {

    $id = (int)$this->_getParam('id');
    $this->view->id = $id;

    $denoms = new Denominations();
    $this->view->denoms = $denoms->getDenom($id,(int)$this->_period);

    $rulers = new Denominations();
    $this->view->rulers = $rulers->getRulerDenomination((int)$id);

    } else {
    throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
 }