<?php
/** Controller for displaying byzantine coins denominations pages with recent examples
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author     Daniel Pett
* @since	  September 2011
*/
class ByzantineCoins_DenominationsController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL and contexts
    */
    public function init(){
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('denomination', array('xml','json'))
            ->initContext();
    }

    protected $_period = 67;

    /** Set up index page for denominations
     */
    public function indexAction() {
    $denominations = new Denominations();
    $this->view->denominations = $denominations->getDenominations($this->_period,
            $this->_getParam('page'));
    }

    /** Set up specific page for a denomination
     */
    public function denominationAction()  {
    if($this->_getParam('id',false)){
    $this->view->id = $this->_getParam('id');
    $denoms = new Denominations();
    $this->view->denoms = $denoms->getDenom($this->_getParam('id'),$this->_period);
    } else {
   	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}