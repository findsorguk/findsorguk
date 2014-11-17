<?php

/** Controller viewing the current configuration variables
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Action
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 */
class Admin_ConfigurationController extends Pas_Controller_Action_Admin
{

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('admin', null);
        $this->_config = $this->_helper->config();

    }

    /** Display the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Magic in view
    }

    /** Display the webservice configurations
     * @access public
     * @return void
     */
    public function webserviceAction()
    {
        $this->view->webservice = $this->_config->webservice->toArray();
    }

    /** Display the system configurations
     * @access public
     * @return void
     */
    public function systemAction()
    {
        $this->view->resources = $this->_config->resources->toArray();
    }

    /** Display the routing configurations
     * @access public
     * @return void
     */
    public function routingAction()
    {
        $this->view->routing = $this->_config->routes->toArray();
    }

    /** Display the ACL config
     * @access public
     * @return void
     */
    public function aclAction()
    {
        $this->view->acl = $this->_config->acl->toArray();
    }

    /** Display salts used
     * @access public
     * @return void
     */
    public function saltsAction()
    {
        $this->view->salt = $this->_config->form->salt;
        $this->view->authority = $this->_config->auth->salt;
    }
}