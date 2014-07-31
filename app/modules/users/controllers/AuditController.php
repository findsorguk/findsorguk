<?php
/** Controller for accessing specific user details for IP logins etc
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Logins
 * @uses Pas_Exception_Param
 *
*/
class Users_AuditController extends Pas_Controller_Action_Admin {

    /** The logins model
     * @access protected
     * @var \Logins
     */
    protected $_logins;

    /** Set up the ACL and contexts
     * @access public
     */
    public function init()  {
        $this->_helper->_acl->allow('member',null);
        $this->_logins = new Logins();
        parent::init();
    }

    /** Display logins by username
     * @access public
     * @return void
     */
    public function loginsAction() {
        $this->view->logins = $this->_logins->myLogins((string)$this->getUsername(), (int)$this->_getParam('page'));
        $this->view->ips = $logins->myIps($this->getUsername());
    }

    /** Display the ISP user has used
     * @access public
     * @return void
     */
    public function ispAction() {
        $this->view->logins = $this->_logins->listIps((int)$this->_getParam('page'));
    }

    /** The ip to users action
     * @access public
     * @throws Pas_Exception_Param
     */
    public function iptousersAction() {
	if($this->_getParam('ip',false)) {
            $ip = $this->_getParam('ip');
            $this->view->headTitle('Users who have used IP address: '. $ip);
            $this->view->logins = $this->_logins->users2Ip($ip);
	} else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
	}
    }

    /** Get the IP history for a user
     * @access public
     * @return void
     */
    public function iphistoryAction(){
        $this->view->ips = $this->_logins->myIps($this->getUsername(), $this->_getParam('page'));
    }
}
