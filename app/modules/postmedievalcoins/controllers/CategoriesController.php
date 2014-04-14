<?php
/** Controller for displaying Post medieval category data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_CategoriesController extends Pas_Controller_Action_Admin {

    protected $_categories;

    /** Set up the ACL and contexts
    */
    public function init() {
    $this->_helper->_acl->allow(null);
    $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addActionContext('index', array('xml','json'))
            ->addActionContext('category', array('xml','json'))
            ->initContext();
    $this->_categories = new CategoriesCoins();
}
    /** Set up the category index pages
    */
    public function indexAction() {
    $this->view->categories = $this->_categories->getCategoriesPeriod(36);
    }
    /** Individual category page
    */
    public function categoryAction() {
    if($this->_getParam('id',false)){
    $this->view->categories = $this->_categories->getCategory($this->_getParam('id'));
    $types = new MedievalTypes();
    $this->view->types = $types->getCoinTypeCategory($this->_getParam('id'));
    $this->view->rulers = $this->_categories->getMedievalRulersToType($this->_getParam('id'));
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}
