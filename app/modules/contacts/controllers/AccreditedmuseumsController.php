<?php
/**
 * Controller for displaying and manipulating accredited museum data
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 *
 *
*/
class Contacts_AccreditedMuseumsController extends Pas_Controller_Action_Admin {

    /**
     *  Initialise the ACL and contexts
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index',$contexts)
                ->initContext();
    }

    /** Set up data for museums index page
     *
     */
    public function indexAction() {
        $museums = new AccreditedMuseums();
        $this->view->museums =  $museums->listMuseums($this->_getAllParams());
    }

    /** Get individual museum data
     */
    public function museumAction(){
        $museum = new AccreditedMuseums();
        $this->view->museum = $museum->fetchRow('id = ' . $this->_getParam('id'));
    }

    /** Map the museums
     * 
     */
    public function mapAction(){
        //All magic in view
    }
}