<?php
/** Controller for index page for database module
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014 Daniel Pett
 * @version 2
 * @uses Content
 * @uses Logins
 * @uses SolrForm
 * @uses Pas_ArrayFunctions
 * 
 */
class Database_IndexController extends Pas_Controller_Action_Admin {
    
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->allow('public',NULL);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
    /** Setup index page
     * @access public
     * @return void
     */
    public function indexAction() {
     
        $content = new Content();
        $this->view->contents = $content->getFrontContent('database');

        $recent = new Logins();
        $this->view->logins = $recent->todayVisitors();

        $form = new SolrForm();
        $form->q->setLabel('Search our database: ');
        $form->setMethod('post');
        $this->view->form = $form;
        if($this->getRequest()->isPost() 
                && $form->isValid($this->_request->getPost())) {
            $functions = new Pas_ArrayFunctions();
            $params = $functions->array_cleanup($form->getValues());
            $this->_flashMessenger->addMessage('Your search is complete');
            $this->_helper->Redirector->gotoSimple(
                    'results', 'search', 'database', $params);
        } else {
            $form->populate($this->_request->getPost());
        }
    }
}