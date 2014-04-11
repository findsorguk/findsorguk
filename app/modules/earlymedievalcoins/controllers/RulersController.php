<?php
/** Controller for displaying Early Medieval coin rulers page
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_RulersController extends Pas_Controller_Action_Admin {

    protected $_rulers;
    /** Initialise the ACL and contexts
    */
    public function init()  {
    $this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('ruler', array('xml','json'))
            ->addActionContext('foreign', array('xml','json'))
            ->initContext();
    $this->_rulers = new Rulers();
    }

    /** Internal period number for querying the database
    */
    protected $_period = 47;
    /** Set up the index page for rulers of each period or dynastic group.
    */
    public function indexAction() {
    $names = new CategoriesCoins();
    $this->view->names = $names->getCategoryName();

    $this->view->allengland = $this->_rulers->getEarlyMedievalRulers('3');

    $this->view->eastanglia = $this->_rulers->getEarlyMedievalRulers('4');

    $this->view->mercia = $this->_rulers->getEarlyMedievalRulers('5');

    $this->view->wessex = $this->_rulers->getEarlyMedievalRulers('6');

    $this->view->canterbury = $this->_rulers->getEarlyMedievalRulers('11');

    $this->view->kent = $this->_rulers->getEarlyMedievalRulers('12');

    $this->view->viking = $this->_rulers->getEarlyMedievalRulers('7');

    $this->view->northumbria = $this->_rulers->getEarlyMedievalRulers('13');

    $this->view->earlysilver = $this->_rulers->getEarlyMedievalRulers('9');

    $this->view->earlygold = $this->_rulers->getEarlyMedievalRulers('8');
    }

    /** Set up the individual page per ruler with examples, map and types
    */
    public function rulerAction() {
    if($this->_getParam('id',false)){
    $id = $this->_getParam('id');

    $this->view->rulers = $this->_rulers->getRulerImage($id);
    $this->view->monarchs = $this->_rulers->getRulerProfileMed($id);

    $denominations = new Denominations();
    $this->view->denominations = $denominations->getEarlyMedRulerToDenomination($id);

    $types = new MedievalTypes();
    $this->view->types = $types->getEarlyMedievalTypeToRuler($id);

    $mints = new Mints();
    $this->view->mints = $mints->getMedMintRuler($id);

    } else {
    throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Set up the foreign rulers page
    */
    public function foreignAction() {
    $names = new CategoriesCoins();
    $this->view->names = $names->getCategoryName();
    $this->view->francia = $this->_rulers->getEarlyMedievalRulers(1);
    $this->view->islamic = $this->_rulers->getEarlyMedievalRulers(10);
    $this->view->hiberno = $this->_rulers->getEarlyMedievalRulers(28);
    $this->view->french  = $this->_rulers->getEarlyMedievalRulers(35);
    }

}