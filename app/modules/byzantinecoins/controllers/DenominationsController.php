<?php
/** Controller for displaying byzantine coins denominations pages with recent examples
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since September 2011
 * @version 1
 * @uses Denominations
 * 
*/
class ByzantineCoins_DenominationsController extends Pas_Controller_Action_Admin {

    /** The denominations model
     * @access protected
     * @var \Denominations
     */
    protected $_denominations;
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init(){
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('denomination', array('xml','json'))
                ->initContext();
        $this->_denominations = new Denominations();
        
    }

    /** The period to query
     * @access protected
     * @var integer
     */
    protected $_period = 67;

    /** Set up index page for denominations
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->denominations = $this->_denominations
                ->getDenominations(
                        $this->_period,
                        $this->getPage()
                        );
    }

    /** Set up specific page for a denomination
     * @access public
     * @return void
     */
    public function denominationAction()  {
        if($this->getParam('id',false)){
            $this->view->id = $this->getParam('id');
            $this->view->denoms = $this->_denominations->getDenom($this->getParam('id'),$this->_period);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}