<?php
/**
 * Controller for displaying and manipulating accredited museum data
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses AccreditedMuseums
 *
*/
class Contacts_AccreditedMuseumsController extends Pas_Controller_Action_Admin {

    protected $_accredited;
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$contexts)
                ->initContext();
        $this->_accredited = new AccreditedMuseums();
    }

    /** Set up data for museums index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->museums =  $this->_accredited->listMuseums($this->_getAllParams());
    }

    /** Get individual museum data
     * @access public
     * @return void
     */
    public function museumAction(){
        $this->view->museum = $this->_accredited->fetchRow('id = ' . $this->_getParam('id'));
    }
    /** Map the museums
     * @access public
     * @return void
     */
    public function mapAction(){
        //All magic in view
    }
}