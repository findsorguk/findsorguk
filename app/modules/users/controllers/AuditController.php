<?php
/** Controller for accessing specific user details for IP logins etc
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_AuditController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init()  {
	$this->_helper->_acl->allow('member',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Display logins by username
	*/
	public function loginsAction() {
	$logins = new Logins();
	$this->view->logins = $logins->myLogins((string)$this->getUsername(), (int)$this->_getParam('page'));
	$this->view->ips = $logins->myIps($this->getUsername());
	}
	/** Display the ISP user has used
	*/
	public function ispAction() {
	$logins = new Logins();
	$this->view->logins = $logins->listIps((int)$this->_getParam('page'));
	}
	/** Work out how many people have used a certain IP address
	*/
	public function iptousersAction() {
	if($this->_getParam('ip',false)) {
	$ip = $this->_getParam('ip');
	$this->view->headTitle('Users who have used IP address: '. $ip);
	$logins = new Logins();
	$this->view->logins = $logins->users2Ip($ip);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

        public function iphistoryAction(){
		$logins = new Logins();
            $this->view->ips = $logins->myIps($this->getUsername(), $this->_getParam('page'));
        }

}
