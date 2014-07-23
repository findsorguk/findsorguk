<?php
/** Controller for all rendering the advice section of the treasure module
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
class Secrettreasures_ContentController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow(null);
    }
    /**  Render the index page
     * @access public
     * @return void
     */
    public function indexAction()	{
        $content = new Content();
        $this->view->contents = $content->getSecretContent('secret',$this->getRequest()->getParam('slug'));
    }
}