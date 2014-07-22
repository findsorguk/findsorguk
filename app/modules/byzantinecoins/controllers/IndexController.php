<?php
/** Controller for displaying byzantine coins index pages with recent examples
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
*/

class ByzantineCoins_IndexController extends Pas_Controller_Action_Admin  {

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init(){
        $this->_helper->_acl->allow(null);
    }

    /** Set up the view for index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->content =  $content->getFrontContent('byzantinecoins');
    }
}