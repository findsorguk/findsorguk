<?php
/** Controller for accessing Bronze Age guide index page
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * 
*/
class Bronzeage_IndexController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL and contexts
     * @access public
     * @return void
    */
    public function init(){
    $this->_helper->_acl->allow(null);
    }

    /** Render the index pages
     * @access public
     * @return void
     */
    public function indexAction(){
        $content = new Content();
        $this->view->content =  $content->getFrontContent('bronzeage');
    }
}