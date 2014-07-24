<?php
/** Controller for manipulating search queries
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Searches
 */
class Admin_SearchController extends Pas_Controller_Action_Admin {

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('admin',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
    /** Paginated list of searches made
     * @access public
     * @return void
     */
    public function indexAction()  {
        $search = new Searches();
        $this->view->searches = $search->getAllSearchesAdmin($this->_getParam('page'));
    }
}