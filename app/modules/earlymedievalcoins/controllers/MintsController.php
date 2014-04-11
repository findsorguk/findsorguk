<?php
/** Controller for displaying Early Medieval coin mints page
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_MintsController extends Pas_Controller_Action_Admin
{
    /** Initialise the ACL and contexts
    */
    public function init() {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('mint', array('xml','json'))
            ->initContext();
    }
    /** Internal period number for querying the database
    */
    protected $_period = 47;

    /** Set up index page for mints
    */
    public function indexAction() {
    $mints = new Mints();
    $this->view->mints = $mints->getListMints($this->_period);
    }
    /** Get details of each individual mint
    * @param int $id mint number
    */
    public function mintAction() {
    if($this->_getParam('id',false)){

    $id = $this->_getParam('id');
    $this->view->id = $id;

    $mints = new Mints();
    $this->view->mints = $mints->getMintDetails($id);

    $actives = new Rulers();
    $this->view->actives = $actives->getMedievalMintRulerList($id);

    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}