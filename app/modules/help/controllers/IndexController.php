<?php
/** Controller for index of help topics
 * 
 * @category Pas 
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Content
 */

class Help_IndexController extends Pas_Controller_Action_Admin {
    
    /** Setup the ACL.
     * @access public
     * @return void
     */
    public function init(){
        $this->_helper->acl->allow('public',null);
    }
    
    /** Display help index 
     * @access public
     * @return void
     */
    public function indexAction(){
        $content = new Content();
        $this->view->contents = $content->getFrontContent('help');
    }
}