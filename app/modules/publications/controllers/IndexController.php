<?php

/** Controller for manipulating publications data
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @author     Daniel Pett <dpett@britishmuseum.org>
 * @copyright  Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * @uses RequestForm
 */
class Publications_IndexController extends Pas_Controller_Action_Admin
{

    /** Initialise the ACL, cache and config
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow(null);

    }

    /** Render documents on the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('publications');
    }
}