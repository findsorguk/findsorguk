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

    protected $_config;

    /** Get the config object
     * @return mixed
     */
    public function getConfig()
    {
        $this->_config = $this->_helper->config();
        return $this->_config;
    }


    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('admin', null);
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
        $this->view->webservice = $this->getConfig()->webservice->toArray();
    }

    /** Display the system configurations
     * @access public
     * @return void
     */
    public function systemAction()
    {
        $this->view->resources = $this->getConfig()->resources->toArray();
    }

    /** Display the routing configurations
     * @access public
     * @return void
     */
    public function routingAction()
    {
        $this->view->routing = $this->getConfig()->routes->toArray();
    }

    /** Display the ACL config
     * @access public
     * @return void
     */
    public function aclAction()
    {
        $this->view->acl = $this->getConfig()->acl->toArray();
    }

    /** Display salts used
     * @access public
     * @return void
     */
    public function saltsAction()
    {
        $this->view->salt = $this->getConfig()->form->salt;
        $this->view->authority = $this->getConfig()->auth->salt;
    }
}