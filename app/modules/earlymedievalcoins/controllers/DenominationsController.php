<?php
/** Controller for displaying Early Medieval coin denominations
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Denominations
 * 
*/
class EarlyMedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {

    /** The denominations model
     * @access protected
     * @var \Denominations
     */
    protected $_denominations;

    /**
     * @return Denominations
     */
    public function getDenominations()
    {
        $this->_denominations = new Denominations();
        return $this->_denominations;
    }


    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
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
     * @access protected
     * @var integer
     */
    protected $_period = 47;

    /** Set up index page for denominations
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->denominations = $this->getDenominations()->getDenominations($this->_period, null);
    }

    /** Get details of each individual denomination
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function denominationAction() {
        if($this->getParam('id',false)) {
        $this->view->id = $this->getParam('id');
        $this->view->denoms = $this->getDenominations()->getDenom($this->getParam('id'),(int)$this->_period);
        $this->view->rulers = $this->getDenominations()->getRulerDenomination($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
 }