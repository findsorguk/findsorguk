<?php
/** Controller for Iron Age Van Ardsell types
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses VanArsdellTypes
 */
class IronAgeCoins_VanarsdelltypesController extends Pas_Controller_Action_Admin {

    /** Setup the contexts by action and the ACL.
     */
    public function init() {
        $this->_types = new VanArsdellTypes();
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                    ->addActionContext('index', array('xml','json'))
                    ->addActionContext('type', array('xml','json'))
                    ->initContext();
    }

    /** Setup the index page for Van Arsdell Types
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->va = $this->_types->getVaTypes($this->_getAllParams());
    }

    /** Get details for a specific type
     * @access public
     * @return void
     */
    public function typeAction(){
        $this->view->type = $this->_types->fetchRow(
                $this->_types->select()->where('type = ?',$this->_getParam('id')));
    }
}