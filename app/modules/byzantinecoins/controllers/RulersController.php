<?php
/** Controller for displaying byzantine ruler pages with recent examples
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Rulers
 * @uses Pas_Exception_Param
*/
class ByzantineCoins_RulersController extends Pas_Controller_Action_Admin {

    /** The rulers model
     * @access protected
     * @var \Rulers
     */
    protected $_rulers;
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->addActionContext('index', array('xml','json'))
                ->addActionContext('ruler', array('xml','json'))
                ->initContext();
        $this->_rulers = new Rulers();
    }
    
    /** Setup the index page for rulers
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->rulers = $this->_rulers->getRulersByzantineList($this->_getParam('page'));
    }

    /** Get individual ruler page
    */
    public function rulerAction() {
        if($this->_getParam('id',false)){
            $this->view->ruler = $this->_rulers->getRulerProfile((int)$this->_getParam('id'));
            $this->view->id = $this->_getParam('id');
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }


}