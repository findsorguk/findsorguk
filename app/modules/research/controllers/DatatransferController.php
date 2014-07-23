<?php
/** Controller for getting information on data transfer to HERs
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * @uses Hers
*/
class Research_DatatransferController extends Pas_Controller_Action_Admin {
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
    }
	
    /** Get data for data transfer index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('datatransfer');
    }
    
    /** Get data for HER page
     * @access public
     * @return void
     */
    public function hersAction() {
        $hers = new Hers();
        $this->view->hers = $hers->getAll($this->_getAllParams());
    }
	
}