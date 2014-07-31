<?php
/** Index controller for admin section
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Events
 */
class Admin_IndexController extends Pas_Controller_Action_Admin {

    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $flosActions = array('index',);
        $faActions = array(
            'addcontent', 'content', 'editcontent',
            'addperiod', 'editperiod', 'deleteperiod',
            'addmedievalruler', 'editmedievalruler', 'editmethod',
            'emperorbios', 'numismatics'
            );
        $this->_helper->_acl->allow('flos',$flosActions);
        $this->_helper->_acl->allow('fa',$faActions);
        $this->_helper->_acl->allow('admin',null);
        
    }
    
    /** The index page for the admin section
     * @access public
     * @return void
     */
    public function indexAction() {
	$events = new Events();
	$this->view->events = $events->getUpcomingEvents();
    }
}