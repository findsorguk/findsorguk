<?php
/** Controller for displaying Roman republican moneyers
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Moneyers
 * @uses Pas_Exception_Param
 */
class Romancoins_MoneyersController extends Pas_Controller_Action_Admin {

    /** The moneyers model
     * @access protected
     * @var \Moneyers
     */
    protected $_moneyers;

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
                ->addActionContext('called',$contexts)
                ->initContext();
        $this->_moneyers = new Moneyers();
    }
    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->moneyers = $this->_moneyers->getValidMoneyers($this->_getAllParams());
    }
    /** Set up the moneyer individual pages
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function calledAction() {
        if($this->_getParam('by',false)){
            $this->view->moneyer = $this->_moneyers->getMoneyer($this->_getParam('by'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}