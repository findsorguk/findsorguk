<?php
/** Controller for Iron Age geographical regions
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version    1
 * @since      2011
 * @uses Geography
 * @uses Denominations
 * @uses Rulers
 * @uses Pas_Exception_Param
*/
class IronAgeCoins_RegionsController extends Pas_Controller_Action_Admin {

    /** Geography var
     * @access protected
     * @var \Geography
     */
    protected $_geography;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('region', array('xml','json'))
                ->initContext();
        $this->_geography = new Geography();
    }

    /** Internal period ID number for the Iron Age
     * @access protected
     * @var integer
     */
    protected $_period = 16;

    /** Setup the index page for Iron Age geography
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->regions = $this->_geography->getIronAgeRegions();
    }

    /** Individual region's details
     * @access public
     * @throws Pas_Exception_Param
     *
    */
    public function regionAction(){
        if($this->_getParam('id',false)){
            $this->view->regions = $this->_geography->getIronAgeRegion($this->_getParam('id'));
            $id = $this->_getParam('id');
            $denominations = new Denominations();
            $this->view->denominations = $denominations->getDenByPeriod($this->_period);
            $rulers = new Rulers();
            $this->view->rulers = $rulers->getIronAgeRulerToRegion($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
 }