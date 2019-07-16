<?php

class Admin_SslController extends Pas_Controller_Action_Admin
{
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public');
    }

    // Path for the file
    const PATH = './.well-known/acme-challenge/';

    // Display the string from the file
    public function indexAction()
    {
	header("Content-Type: text/plain");

	echo file_get_contents(self::PATH . $this->getRequest()->getParam('slug'));
	exit;
    }
}
