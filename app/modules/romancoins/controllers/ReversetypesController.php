<?php
/** Controller for displaying Roman reverse types
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses RevTypes
 * @uses Pas_Exception_Param
 * @uses Mints
 * @uses Emperors
 * 
 */
class RomanCoins_ReverseTypesController extends Pas_Controller_Action_Admin {
    
    /** The rev types model
     * @access protected
     * @var \RevTypes
     */
    protected $_revTypes;
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $contexts = array('xml','json');
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$contexts)
                ->addActionContext('reversetype',$contexts)
                ->initContext();
        $this->_revTypes = new RevTypes();
    }
    
    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->reverses = $this->_revTypes->getReverseTypeList(1);
        $this->view->uncommonreverses = $this->_revTypes->getReverseTypeList(2);
    }
    /** Set up the individual reverse type
    */		
    public function typeAction() {
        if($this->_getParam('id',false)) {
            $id = $this->_getParam('id');
            $this->view->reverses = $this->_revTypes->getReverseTypesDetails($id);
            $emps = new Emperors();
            $this->view->emps = $emps->getEmperorRevTypes($id);
            $mints = new Mints();
            $this->view->mints = $mints->getMintReverseType($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}