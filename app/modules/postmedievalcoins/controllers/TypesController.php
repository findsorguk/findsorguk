<?php
/** Controller for displaying Post medieval type lists
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses MedievalTypes
 * @uses Pas_Exception_Param
 * 
 */
class PostMedievalCoins_TypesController extends Pas_Controller_Action_Admin {

    /** The medieval types
     * @access protected
     * @var \MedievalTypes
     */
    protected $_types;

    /** The internal ID number
     * @access protected
     * @var integer
     */    
    protected $_period = 36;
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        
        $this->_helper->contextSwitch->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()
                ->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('type', array('xml','json'))
                ->initContext();
        $this->_types = new MedievalTypes();
    }


    /** Index page for the Post Medieval types list
     * @access public
     * @return void
     */
    public function indexAction(){
        $this->view->types = $this->_types
                ->getTypesByPeriod($this->_period,$this->_getParam('page'));
    }

    /** Individual details for Post Medieval types
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function typeAction() {
        if($this->_getParam('id',false)){
            $this->view->types = $this->_types->getTypeDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}