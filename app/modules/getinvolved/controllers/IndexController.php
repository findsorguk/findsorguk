<?php
/** Controller for index of the get involved module
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
*/
class GetInvolved_IndexController extends Pas_Controller_Action_Admin {
	
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        
        $this->view->messages = $this->getFlash()->getMessages();
        $this->_helper->acl->allow('public',null);
    }
	
    /** The index action to display intro text
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('getinvolved');
    }
}