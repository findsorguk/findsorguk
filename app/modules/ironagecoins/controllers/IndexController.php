<?php
/** Controller for Iron Age period's index page
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
class IronAgeCoins_IndexController extends Pas_Controller_Action_Admin {
	
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow(null);
    }

    /** Set up data for the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->content =  $content->getFrontContent('ironagecoins');
    }
}
