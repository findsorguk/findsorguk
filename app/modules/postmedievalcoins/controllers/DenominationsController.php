<?php
/** Controller for displaying Post medieval coins index pages
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author     Daniel Pett
* @version    1
*/
class PostMedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {

     /** Internal period ID number
	*/
    protected $_period = 36;

    protected $_denominations;


    /** Set up the ACL and contexts
     *
     *
     */
    public function init() {
    $this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('denomination', array('xml','json'))
		->initContext();
    $this->_denominations = new Denominations();
    }


    /** Denomination index pages
     * @access Public
     *
     */
    public function indexAction() {
    $this->view->denominations = $this->_denominations->getDenominations($this->_period,
            $this->_getParam('page'));
    }

    /** Individual denomination page details
     * @access Public
     * @throws Pas_Exception_Param
     */
    public function denominationAction()  {
    if($this->_getParam('id',false)){
    $id = $this->_getParam('id');
    $this->view->denomination = $this->_denominations->getDenom($id,(int)$this->_period);

    $this->view->rulers = $this->_denominations->getRulerDenomination($id);

    } else {
    	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}