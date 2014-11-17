<?php

/** Controller for displaying information topics
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Help
 * @version 1
 *
 */
class Info_AdviceController extends Pas_Controller_Action_Admin
{

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
    }

    /** Display the list of topics or individual pages.
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->contents = $content->getContent('info',
            $this->_getParam('slug'));
    }
}