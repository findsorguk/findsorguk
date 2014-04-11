<?php
/** Controller for displaying Early Medieval coin categories
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_CategoriesController extends Pas_Controller_Action_Admin
{
    /** Initialise the ACL and contexts
    */
    public function init()  {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('category', array('xml','json'))
            ->initContext();
    }

    /** Internal period number for querying the database
    */
    protected $_period = 47;

    /** Set up index page for categories
    */
    public function indexAction() {
            $categories = new CategoriesCoins();
            $this->view->categories = $categories->getCategoriesPeriod($this->_period);
    }

    /** Get details of each individual category
    * @param int $id Category number
    */
    public function categoryAction() {
    if($this->_getParam('id', false)) {
            $id = (int)$this->_getParam('id');

            $categories = new CategoriesCoins();
            $this->view->categories = $categories->getCategory($id);

            $types = new MedievalTypes();
            $this->view->types = $types->getCoinTypeCategory($id);

            $rulers =  new CategoriesCoins();
            $this->view->rulers = $rulers->getMedievalRulersToType($id);

    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}
