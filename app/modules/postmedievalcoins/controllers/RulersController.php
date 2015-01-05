<?php
/** Controller for displaying Post medieval rulers data
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Rulers
 * @uses Pas_Exception_Param
 * @uses Denominations
 * @uses MedievalTypes
 * @uses Mints
 */
class PostMedievalCoins_RulersController extends Pas_Controller_Action_Admin {

    /** The rulers model
     * @access protected
     * @var \Rulers
     */
    protected $_rulers;

    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('ruler', array('xml','json'))
		->addActionContext('foreign', array('xml','json'))
		->addActionContext('data', array('json'))
		->initContext();
        $this->_rulers = new Rulers();
    }
    
    /** Internal period ID number
     * @access protected
     * @var integer
     */
    protected $_period = 36;

    /** Index page for Post Medieval rulers
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->rulers = $this->_rulers->getMedievalRulersListedMain($period = $this->_period);
    }
    
    /** Individual ruler page
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function rulerAction() {
        if($this->getParam('id',false)){
            $id = $this->getParam('id');
            $this->view->rulers = $this->_rulers->getRulerImage($id);
            $this->view->monarchs = $this->_rulers->getRulerProfileMed($id);
            $denominations = new Denominations();
            $this->view->denominations = $denominations->getEarlyMedRulerToDenomination($id);
            $types = new MedievalTypes();
            $this->view->types = $types->getPostMedievalTypeToRuler($id);
            $mints = new Mints();
            $this->view->mints = $mints->getMedMintRuler($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
    /** List of foreign Post medieval rulers
     * @access public
     * @return void
     */
    public function foreignAction() {
        $this->view->doges = $this->_rulers->getForeign($this->_period, 1);
        $this->view->scots = $this->_rulers->getForeign($this->_period, 2);
        $this->view->low = $this->_rulers->getForeign($this->_period, 3);
        $this->view->imitate = $this->_rulers->getForeign($this->_period, 4);
        $this->view->portugal = $this->_rulers->getForeign($this->_period, 5);
        $this->view->shortlongs = $this->_rulers->getForeign($this->_period, 6);
        $this->view->france = $this->_rulers->getForeign($this->_period, 7);
    }

    /** The timeline of kings
     * @access public
     * @return void
     */
    public function timelineAction() {
        $this->_helper->layout->disableLayout();
    }

    /** The data action for the timeline
     * @access public
     * @return void
     */
    public function dataAction(){
        $this->view->rulers = $this->_rulers->getMedievalRulersListedMain($period = $this->_period);
    }
}