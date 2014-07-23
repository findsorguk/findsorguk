<?php
/** Controller for displaying denominations from the Greek and Roman provincial world
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Denominations
 * @uses Pas_Exception_Param
 */
class GreekRomanCoins_DenominationsController extends Pas_Controller_Action_Admin {
	
    /** The denominations model
     * @access protected
     * @var /Denominations
     */
    protected $_denominations;

    /** Initialise the ACL and contexts
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
     * @var string
     */
    protected $_period = 66;

    /** Set up the index display page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->denominations = $this->_denominations->getDenominations($this->_period,$this->_getParam('page'));
    }

    /** Display individual denomination
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function denominationAction() {
        if($this->_getParam('id',false)){
            $this->view->denoms = $this->_denominations->getDenom((int)$this->_getParam('id'),(int)$this->_period);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);		
        }
    }
}