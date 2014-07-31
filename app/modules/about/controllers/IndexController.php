<?php
/**  Controller for index of About Us section
 * 
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Content
*/
class About_IndexController extends Pas_Controller_Action_Admin {
	
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init(){
        $this->_helper->acl->allow('public', null);
        
    }
    /** Display the front page material.
     * @access public
     * @return void
     */	
    public function indexAction(){
        $content = new Content();
        $this->view->contents = $content->getFrontContent('about');
    }
}