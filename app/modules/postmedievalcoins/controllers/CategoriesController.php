<?php
/** Controller for displaying Post medieval category data
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin 
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses CategoriesCoins
 * @uses MedievalTypes
*/
class PostMedievalCoins_CategoriesController extends Pas_Controller_Action_Admin {

    /** The coin categories model
     * @access protected
     * @var \CategoriesCoins
     */
    protected $_categories;

    /** Set up the ACL and contexts
     * @access public
     * @return void
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
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->categories = $this->_categories->getCategoriesPeriod(36);
    }
    
    /** Individual category page
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function categoryAction() {
        if($this->getParam('id',false)){
            $this->view->categories = $this->_categories->getCategory($this->getParam('id'));
            $this->view->rulers = $this->_categories->getMedievalRulersToType($this->getParam('id'));
            $types = new MedievalTypes();
            $this->view->types = $types->getCoinTypeCategory($this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
