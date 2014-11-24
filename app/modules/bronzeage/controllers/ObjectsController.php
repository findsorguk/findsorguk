<?php

/** Controller for accessing Bronze Age guide objects page
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 */
class Bronzeage_ObjectsController extends Pas_Controller_Action_Admin
{

    /** Set up ACL
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('public', null);

    }

    /** Render the index pages
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $content = new Content();
        if (!in_array($this->_getParam('slug'), array('gold', 'other'))) {
            $this->view->content = $content->getContent('bronzeage', $this->_getParam('slug'));
        } else {
            if ($this->_getParam('slug') == 'gold') {
                $this->view->content = $content->getContent('bronzeage', $this->_getParam('slug'));
                $this->view->menu = 'gold';
            } else if ($this->_getParam('slug') == 'other') {
                $this->view->content = $content->getContent('bronzeage', $this->_getParam('slug'));
                $this->view->menu = 'other';
            }
        }
    }

}

