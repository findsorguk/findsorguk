<?php
/** Controller for scrollintg through users. Minimum access to members only.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Comments
 * @todo This might be better off putting comments into solr
*/
class Users_CommentsController extends Pas_Controller_Action_Admin {

    /** The comments model
     * @access protected
     * @var \Comments
     */
    protected $_comments;

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init()  {
        $this->_helper->_acl->allow('member',null);
        $this->_comments = new Comments();
        
    }

    /** Pointles function
     * @access public
     * @return array
     */
    public function getParams() {
        return $this->_getAllParams();
    }
    /** No access to the index page, redirect to the comments you made
     * @access public
     * @return @return void
     */
    public function indexAction(){
	return $this->redirect('/users/comments/imade/');
    }

    /** Comments made my user
     * @access public
     * @return void
     */
    public function imadeAction() {
        $this->view->params = $this->getParams();
        $this->view->comments = $this->_comments
                ->getComments($this->getParams(), $this->getIdentityForForms());
    }

    /** Comments on your records
     * @access public
     * @return void
     */
    public function onmineAction() {
        $this->view->params = $this->getParams();
        $this->view->comments = $this->_comments
                ->getCommentsOnMyRecords(
                        $this->getIdentityForForms(),
                        $this->_getParam('page'),
                        $this->_getParam('approved')
                        );
    }
}