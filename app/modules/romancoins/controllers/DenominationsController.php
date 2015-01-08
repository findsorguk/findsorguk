<?php
/** Controller for displaying Roman denominations
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Denominations
 * @uses Emperors
 * @uses Pas_Exception_Param
 * 
*/
class RomanCoins_DenominationsController extends Pas_Controller_Action_Admin {

    /** The denominations model
     * @access protected
     * @var type 
     */
    protected $_denominations;
        
    /** The contexts array
     * @access protected
     * @var array
     */
    protected $_contexts = array('xml', 'json');
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$this->_contexts)
                ->addActionContext('denomination',$this->_contexts)
                ->initContext();
        $this->_denominations = new Denominations();
        
    }
    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->denominations = $this->_denominations->getDenByPeriod((int)21);
    }
    
    /** Set up the individual denominations
     * @access public
     * @throws Pas_Exception_Param
     * @return void
     */
    public function denominationAction() {
        if($this->getParam('id',false)) {
            $id = $this->getParam('id');
            $this->view->denoms = $this->_denominations->getDenom($id,(int)21);
            $emps = new Emperors();
            $this->view->emps = $emps->getDenomEmperor($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
