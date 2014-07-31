<?php
/** Controller for displaying byzantine mint pages with recent examples
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Mints
 * @uses Pas_Exception_Param
*/
class ByzantineCoins_MintsController extends Pas_Controller_Action_Admin {
    
    /** The mints model
     * @access protected
     * @var \Mints
     */
    protected $_mints;
	
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('mint', array('xml','json'))
                ->initContext();
        $this->_mints = new Mints();
        
    }

    /** Set up the index pages
     * @access public
     * @return void
    */
    public function indexAction() {
        $this->view->mints = $this->_mints->getMintsByzantineList();
    }
    
    /** Set up the specific mint page
     * @access public
     * @throws Pas_Exception_Param
     */
    public function mintAction() {
        if($this->_getParam('id',false)){
            $this->view->mints = $this->_mints->getMintDetails($this->_getParam('id'));
            $this->view->id = $this->_getParam('id');
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}