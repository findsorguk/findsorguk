<?php
/** Controller for displaying Early Medieval coin types page
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses MedievalTypes
 * @uses Pas_Exception_Param
*/
class Earlymedievalcoins_TypesController extends Pas_Controller_Action_Admin {

    /** The medieval type model
     * @access protected
     * @var \MedievalTypes
     */
    protected $_types;

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
		->addActionContext('type', array('xml','json'))
		->initContext();
        $this->_types = new MedievalTypes();
    }

    /** Internal period number for querying the database
     * @access protected
     * @var integer
     */
    protected $_period = '47';

    /** Set up the index page for early medieval types.
     * @access public
     * @return void
    */
    public function indexAction() {
        $this->view->types = $this->_types
                ->getTypesByPeriod($this->_period, $this->getPage());
    }

    /** Set up the individual types
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function typeAction() {
        if($this->_getParam('id',false)){
            $this->view->id = $this->_getParam('id');
            $this->view->types = $this->_types->getTypeDetails($this->_getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}