<?php
/** Controller for displaying Medieval coin categories
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @uses CategoriesCoins
 * @uses Pas_Exception_Param
 * @uses MedievalTypes
*/
class MedievalCoins_CategoriesController extends Pas_Controller_Action_Admin {
	
    /** The categories model
     * @access protected
     * @var \CategoriesCoins
     */
    protected $_categories;

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
        
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->addActionContext('category', array('xml','json'))
                ->initContext();
        $this->_categories = new CategoriesCoins();
    }
    /** Internal period ID number
     * @access protected
     * @var integer
     */
    protected $_period = 29;

    /** Setup the index action for Medieval categories
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->categories = $this->_categories->getCategoriesPeriod($this->_period);
    }

    /** Individual category details.
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function categoryAction(){
        if($this->_getParam('id',false)){	
            $id = $this->_getParam('id');
            $this->view->categories = $this->_categories->getCategory($id);
            $types = new MedievalTypes();
            $this->view->types = $types->getCoinTypeCategory($id);
            $this->view->rulers = $this->_categories->getMedievalRulersToType($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}