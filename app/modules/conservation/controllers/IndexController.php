<?php
/** Controller for displaying index page for the conservation notes module.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * 
 */

class Conservation_IndexController extends Pas_Controller_Action_Admin {

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow('public',null);
    }
	
    /** Set up view for index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('conservation');
    }
}