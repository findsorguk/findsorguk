<?php

/** Volunteer recording guide page controller
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @since May 14 2014
 * @filesource /app/modules/volunteerrecording/controllers/IndexController.php
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Mary Chester-Kadwell
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @uses Content
 * @uses Pas_Exception_Param
 */
class Volunteerrecording_GuideController extends Pas_Controller_Action_Admin
{

    /** Initiate the ACL
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);
    }

    /** The default action - show the home page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        if ($this->getParam('slug', 0)) {
            $content = new Content();
            $this->view->content = $content->getContent('frg',
                $this->getParam('slug'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}