<?php
/** Controller for Iron Age geographical regions
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author     Daniel Pett
* @version    1
* @since      2011
*/
class IronAgeCoins_RegionsController extends Pas_Controller_Action_Admin {

    /** Geography var
     *
     * @var object
     */
    protected $_geography;

    /** Setup the contexts by action and the ACL.
     *
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
    */
    protected $_period = 16;

    /** Setup the index page for Iron Age geography
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
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

 }