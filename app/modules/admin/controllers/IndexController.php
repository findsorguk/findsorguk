<?php
/** Index controller for admin section
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_IndexController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init() {
		$flosActions = array('index',);
		$faActions = array('addcontent','content','editcontent','addperiod','editperiod','deleteperiod','addmedievalruler','editmedievalruler','editmethod','emperorbios','numismatics');
 		$this->_helper->_acl->allow('flos',$flosActions);
		$this->_helper->_acl->allow('fa',$faActions);
 		$this->_helper->_acl->allow('admin',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->messages = $this->_flashMessenger->getMessages();
    }
	/** Display index page
	*/
	public function indexAction() {
	$events = new Events();
	$this->view->events = $events->getUpcomingEvents();
    }
}