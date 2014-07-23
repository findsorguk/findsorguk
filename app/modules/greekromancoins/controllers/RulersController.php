<?php
/** Controller for displaying rulers from the Greek and Roman provincial world
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Rulers The rulers model
 * @uses Pas_Exception_Param A parameter missing exception
*/
class GreekRomanCoins_RulersController extends Pas_Controller_Action_Admin {
	
    /** The rulers model
     * @access protected
     * @var \Rulers
     */
    protected $_rulers;
	
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init(){
 	$this->_helper->_acl->allow(null);
 	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('ruler', array('xml','json'))
                ->initContext();
        $this->_rulers = new Rulers();
    }
	
    /** Internal period number
     * @access protected
     * @var integer 
     */
    protected $_period = 66;

    /** The index page for listing rulers
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->greeks = $this->_rulers->getRulersGreekList($this->_getAllParams());
    }
	
    /** Individual ruler page
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function rulerAction() {
        if($this->_getParam('id',false)){
            $this->view->greek= $this->_rulers->getRulerProfile($this->_getParam('id'));
	} else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);		
        }
    }
}