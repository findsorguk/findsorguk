<?php
/** Controller for displaying Early Medieval coin mints page
 *
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Mints
 * @uses Rulers
 * @uses Pas_Exception_Param
 */
class EarlyMedievalCoins_MintsController extends Pas_Controller_Action_Admin
{
    /** Initialise the ACL and contexts
     * @access public
     * @return void
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
     * @access protected
     * @var integer
     */
    protected $_period = 47;

    /** Set up index page for mints
     * @access public
     * @return void
     */
    public function indexAction() {
        $mints = new Mints();
        $this->view->mints = $mints->getListMints($this->_period);
    }
    
    /** Get details of each individual mint
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function mintAction() {
        if($this->getParam('id',false)){
            $id = $this->getParam('id');
            $this->view->id = $id;
            $mints = new Mints();
            $this->view->mints = $mints->getMintDetails($id);
            $actives = new Rulers();
            $this->view->actives = $actives->getMedievalMintRulerList($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}