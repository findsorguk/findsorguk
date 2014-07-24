<?php
/** Controller for displaying Medieval denominations
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Denominations
 * @uses Pas_Exception_Param
 */
class MedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {
	
    /** The denominations
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
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('denomination', array('xml','json'))
                ->initContext();
        $this->_denominations = new Denominations();
    }
    
    /** The period number
     * @access protected
     * @var integer
     */
    protected $_period = 29;

    /** Setup index page for Medieval denominations
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->denominations = $this->_denominations
                ->getDenominations(
                        (int)$this->_period, 
                        (int)$this->_getParam('page')
                        );
    }
	
    /** Setup the denomination details
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function denominationAction() {
        if($this->_getParam('id',false)){
            $id = (int)$this->_getParam('id');
            $this->view->denoms = $this->_denominations->getDenom($id,$this->_period);
            $this->view->rulers = $this->_denominations->getRulerDenomination($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
