<?php
/** Controller for displaying search history for a user's account
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @uses Searches
*/
class Users_SearchController extends Pas_Controller_Action_Admin  {

    /** The searches model
     * @access protected
     * @var \Searches
     */
    protected $_searches;

    /** Setup the ACL
     * @access public
     * @return void
     */
    public function init()  {
        $this->_searches = new Searches();
        $this->_helper->_acl->deny('public');
        $this->_helper->_acl->allow('member',null);
        parent::init();
    }

    /** Setup the index display pages
     * @access public
     * @return void
     */
    public function indexAction()  {
        $this->view->tops = $this->_searches->getTopSearch((int)$this->getIdentityForForms());
        $this->view->quantity = $this->_searches->getTopSearchQuantity((int)$this->getIdentityForForms());
    }

    /** Display the search history for a user
     * @access public
     * @return void
     */
    public function historyAction()  {
        $this->view->searches = $this->_searches->getAllSearches((int)$this->getIdentityForForms(),
        (int)$this->_getParam('page'));
    }

    /** Display saved searches by logged in account
     * @access public
     * @return void
     */
    public function savedAction()  {
    $this->view->searches = $this->_searches->getAllSavedSearches(
            $this->getIdentityForForms(),
            $this->_getParam('page'), null
            );
    }
}