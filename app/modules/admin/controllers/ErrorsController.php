<?php
/** Controller for getting lists of error reports submitted by public
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses ErrorReports
 */
class Admin_ErrorsController extends Pas_Controller_Action_Admin {
    
    /** Set up the ACL and contexts
     * @access public 
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('fa',null);
        $this->_helper->_acl->allow('admin',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
    /** Display the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->params = $this->_getAllParams();
        $errors = new ErrorReports();
        $this->view->errors = $errors->getMessages($this->_getAllParams());
    }
}