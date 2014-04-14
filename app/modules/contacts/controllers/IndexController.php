<?php
/** Controller for all our contacts
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Contacts_IndexController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
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