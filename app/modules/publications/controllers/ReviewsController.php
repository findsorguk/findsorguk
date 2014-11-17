<?php

/** Controller for all the Scheme's commissioned reviews
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @author     Daniel Pett <dpett@britishmuseum.org>
 * @copyright  Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 */
class Publications_ReviewsController extends Pas_Controller_Action_Admin
{

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);

    }

    /** Render index pages
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->contents = $content->getContent('reviews', $this->_getParam('slug'));
    }
}