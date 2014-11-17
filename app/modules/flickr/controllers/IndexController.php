<?php

/** Controller for displaying index page of the flickr module
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 *
 */
class Flickr_IndexController extends Pas_Controller_Action_Admin
{

    /** Init the controller actions
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
    }

    /** The index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Magic in view
    }
}