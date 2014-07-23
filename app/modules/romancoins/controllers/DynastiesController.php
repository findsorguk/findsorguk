<?php
/** Controller for displaying Roman dynasties
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Dynasties
 * @uses Pas_Exception_Param
 */
class RomanCoins_DynastiesController extends Pas_Controller_Action_Admin {
	
    /** The contexts array
     * @access protected
     * @var array
     */
    protected $_contexts = array('xml', 'json');

    /** The dynasties model
     * @access protected
     * @var \Dynasties
     */
    protected $_dynasties;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$this->_contexts)
                ->addActionContext('dynasty',$this->_contexts)
                ->initContext();
        $this->_dynasties = new Dynasties();
    }

    /** Set up the index pages
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->dynasties = $this->_dynasties->getDynastyList();
    }
    /** Set up the individual dynasty
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function dynastyAction() {
        if($this->_getParam('id',false)) {
            $this->view->dynasties = $this->_dynasties->getDynasty($this->_getParam('id'));
            $emperors = new Emperors();
            $this->view->emperors = $emperors->getEmperorsDynasty($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}