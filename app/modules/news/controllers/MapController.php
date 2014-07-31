<?php
/** Controller for news map
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @author     Daniel Pett <dpett@britishmuseum.org>
 * @copyright  Daniel Pett 2011 <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class News_MapController extends Pas_Controller_Action_Admin {
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */ 
    public function init() {
 	$this->_helper->_acl->allow(null);
        
    }

    /** Initialise index page. All data in the view.
     * @access public
     * @return void
     */ 
    public function indexAction(){
        //No model manipulation, all in the view
    }
}