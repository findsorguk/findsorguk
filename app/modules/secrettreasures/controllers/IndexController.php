<?php
/** A controller for manipulating content for the Secret Treasure module
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 */
class Secrettreasures_IndexController extends Pas_Controller_Action_Admin {
    
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->acl->allow(null);
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('secret');
    }
}