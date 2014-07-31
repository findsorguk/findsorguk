<?php 
/** Controller for all rendering index pages of the Treasure module
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @copyright (c) 2014 Daniel Pett
 * @uses Content
*/
class Treasure_IndexController extends Pas_Controller_Action_Admin {
	
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow(null);
        
    }
	
    /** The index action
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('treasure');	
    }
}