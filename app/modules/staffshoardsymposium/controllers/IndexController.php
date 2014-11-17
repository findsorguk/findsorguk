<?php

/** Controller for the Staffordshire symposium
 *
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Content
 */
class Staffshoardsymposium_IndexController extends Pas_Controller_Action_Admin
{

    /** The init function
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);

    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        $this->view->front = $content->getFrontContent('staffs', 1, 3);
        $this->view->contents = $content->getSectionContents('staffs');
    }
}