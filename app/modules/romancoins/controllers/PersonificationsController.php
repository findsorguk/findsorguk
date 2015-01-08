<?php
/** Controller for displaying Roman personifications lists
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Reverses
 * @uses Pas_Exception_Param
 * 
 */
class RomanCoins_PersonificationsController extends Pas_Controller_Action_Admin {
	
    /** The reverses model
     * @access protected
     * @var \Reverses
     */
    protected $_reverses;
	
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $contexts = array('xml','json');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$contexts)
                ->addActionContext('named',$contexts)
                ->initContext();
        $this->_reverses = new Reverses();
        
    }

    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->gods =  $this->_reverses->getPersonifications('god');
        $this->view->virtues = $this->_reverses->getPersonifications('virtue');
        $this->view->symbols = $this->_reverses->getPersonifications('symbol');
    }
    /** Set up the individual named personification
     * @access public
     * @throws Pas_Exception_Parampub   
     * @return void
     */
    public function namedAction() {
        if($this->getParam('as',false)) {
            $this->view->details =  $this->_reverses->getPersonification($this->getParam('as'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}