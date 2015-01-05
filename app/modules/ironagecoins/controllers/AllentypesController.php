<?php
/** Controller for displaying Iron Age coins Allen Types
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses AllenTypes
 * @uses Pas_Exception_Param
 */
class IronAgeCoins_AllentypesController extends Pas_Controller_Action_Admin {
	
    /** The allen Types model
     * @access public
     * @var \Allen_Types
     */
    protected $_allenTypes;
	
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
  		->addActionContext('type', array('xml','json'))
		->initContext();
	$this->_allenTypes = new AllenTypes();
    }
    
    /** Create index pages for Allen Types available to the user
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->allens = $this->_allenTypes
                ->getAllenTypes($this->getAllParams());
    }

    /** An allen type's details
     * @access public
     * @throws Pas_Exception_Param
     * @return void
     */
    public function typeAction(){
        if($this->getParam('id',false)){
        $this->view->type = $this->_allenTypes->fetchRow(
                $this->_allenTypes->select()
                ->where('type = ?', $this->getParam('id'))
                );
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}