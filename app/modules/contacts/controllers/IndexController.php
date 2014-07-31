<?php
/** Controller for all our contacts
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Contacts
 * 
*/
class Contacts_IndexController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',null);
        
        $contexts = array('xml','json','kml','foaf');
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()
                ->setAutoDisableLayout(true)
                ->addContext('kml',array('suffix' => 'kml'))
                ->addContext('foaf',array('suffix' => 'foaf'))
                ->addActionContext('index',$contexts)
                ->initContext();
    }

    /** Set up view for index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $contacts = new Contacts();
        if(!in_array($this->_helper->contextSwitch()->getCurrentContext(),array(
        'kml','json','rss','atom','foaf','xml'))) {
            $this->view->centrals = $contacts->getCentralUnit();
            $this->view->flos = $contacts->getLiaisonOfficers();
            $this->view->treasures = $contacts->getTreasures();
            $this->view->advisers = $contacts->getAdvisers();
            $this->view->schemes = $contacts->getCurrent();
        } else {
            $this->view->staff = $contacts->getCurrent();
        }
    }
}