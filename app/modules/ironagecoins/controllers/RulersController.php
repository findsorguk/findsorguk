<?php
/** Controller for Iron Age period's rulers
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Rulers
 * @uses Pas_Exception_Param
 * @uses Geography
 */
class IronAgeCoins_RulersController extends Pas_Controller_Action_Admin {

    /** The rulers model
     * @access protected
     * @var \Rulers
     */
    protected $_rulers;
    /** Setup the contexts by action and the ACL.
     *
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                    ->addActionContext('index', array('xml','json'))
                    ->addActionContext('ruler', array('xml','json'))
                    ->initContext();
        $this->_rulers = new Rulers();
    }

    /** Setup the index page of Iron Age rulers in a list
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->rulers = $this->_rulers->getIronAgeRulersListed();
    }


    /** Set up an individual's details
     * @access public
     * @return void
     */
    public function rulerAction() {
        if($this->_getParam('id',false)){
            $id = (int)$this->_getParam('id');
            $this->view->id = $id;
            $this->view->rulers = $this->_rulers->getIronAgeRuler($id);
            $regions = new Geography();
            $this->view->regions = $regions->getIronAgeRegionToRuler($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

}