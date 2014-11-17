<?php

/** Volunteer recording guide index module
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @since May 14 2014
 * @filesource /app/modules/volunteerrecording/controllers/IndexController.php
 * @license GNU
 * @author Mary Chester-Kadwell
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Content
 */
class Volunteerrecording_IndexController extends Pas_Controller_Action_Admin
{

    /** Initiate the acl
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
    }

    /** Display the index page for the finds recording guide
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->front = $content->getFrontContent('frg', 1, 3);
        $this->view->contents = $content->getSectionContents('frg');
    }
}