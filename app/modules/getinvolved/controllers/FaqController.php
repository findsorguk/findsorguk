<?php
/** Controller for manipulating the FAQ data
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Faqs
*/
class GetInvolved_FaqController extends Pas_Controller_Action_Admin {
	
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow('public',null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
                ->initContext();
    }
		
   /** Show all frequently asked questions
    * @access public
    * @return void
    */
    public function indexAction() {
        $faqs = new Faqs();
        $this->view->faqs = $faqs->getAll();
    }
}
