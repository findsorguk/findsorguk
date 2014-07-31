<?php
/** Controller for displaying Roman index pages
 *
 * @category   Pas 
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * 
 */
class RomanCoins_IndexController extends Pas_Controller_Action_Admin {
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->allow(null);
        
    }
    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->front =  $content->getFrontContent('romancoins');
    }
}
