<?php 
/** Controller for getting links via delicious
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class Getinvolved_LinksController extends Pas_Controller_Action_Admin {
    
    /** Initialise the ACL
    */ 
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow(null);
    }
    /** Render data for the index page
     * @access public
     * @return void
    */ 
    public function indexAction() {
        $this->view->page = $this->_getParam('page');
    }
    /** Render data by tag for link page
     * @access public
     * @return void
     */
    public function linkAction() {
        $this->view->tag = $this->_getParam('bytag');
    }
}