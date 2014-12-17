<?php
/** Controller for scrollintg through users. Minimum access to members only.
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Users
 */
class Users_NamedController extends Pas_Controller_Action_Admin {

    /** The users model
     * @access protected
     * @var \Users
     */
    protected $_users;

    /** Get the users model
     * @access public
     * @return \Users
     */
    public function getUsers()
    {
        $this->_users = new Users();
        return $this->_users;
    }

    /** The finds model
     * @var null */
    protected $_finds;

    /** get the finds model
     * @access public
     * @return \Finds
     */
    public function getFinds()
    {
        $this->_finds = new Finds();
        return $this->_finds;
    }

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('member',null);

    }

    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction(){
        $this->view->users = $this->getUsers()->getUsersAdmin($this->getAllParams());
    }
    
    /** View the individual person's account
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function personAction() {
        if($this->_getParam('as',0)){
            $this->view->accountdata = $this->getUsers()->getUserAccountData($this->_getParam('as'));
            $this->view->totals = $this->getFinds()->getCountFinds($this->getIdentityForForms());
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}