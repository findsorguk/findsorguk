<?php
/** Controller for displaying Post medieval coins index pages
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Denominations
 * @uses Pas_Exception_Param
*/
class PostMedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {
    
    /** internal period ID number
     * @access protected
     * @var integer
     */
    protected $_period = 36;

    /** The denominations model
     * @access protected
     * @var \Denominations
     */
    protected $_denominations;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        
        $this->_helper->contextSwitch->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()
                    ->setAutoDisableLayout(true)
                    ->addActionContext('index', array('xml','json'))
                    ->addActionContext('denomination', array('xml','json'))
                    ->initContext();
        $this->_denominations = new Denominations();
    }

    /** Denomination index pages
     * @access Public
     * @return void
     */
    public function indexAction() {
        $this->view->denominations = $this->_denominations->getDenominations($this->_period,
                $this->getParam('page'));
    }

    /** Individual denomination page details
     * @access Public
     * @throws Pas_Exception_Param
     */
    public function denominationAction()  {
        if($this->getParam('id',false)){
            $this->view->denomination = $this->_denominations
                    ->getDenom($this->getParam('id'),(int)$this->_period);
            $this->view->rulers = $this->_denominations
                    ->getRulerDenomination($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}