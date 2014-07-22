<?php
/** Controller for former staff
 * 
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Contacts
 * @uses Finds
 * @uses Pas_Exception_Param
*/
class About_AlumniController extends Pas_Controller_Action_Admin {
    
    /** The contacts model
     * @access protected
     * @var \Contacts
     */
    protected $_contacts;
    
    /** The finds model
     * @access protected
     * @var \Finds
     */
    protected $_finds;
    
    /** Initialise the ACL and contexts
     * @access public
     * void
     */
    public function init() {
        $this->_contacts = new Contacts();
        $this->_finds = new Finds();
        $this->_helper->_acl->allow('public',null);
    }

    /** The index action
     * @access public
     */
    public function indexAction() {
        $this->view->alumni = $this->_contacts->getAlumniList();
    }
	
    /** Set up view for individual contact
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function profileAction() {
        if($this->_getParam('id',false)){
            $id = $this->_getParam('id');
            $this->view->staffs = $this->_contacts->getPersonDetails($id);
            $this->view->findstotals = $this->_finds->getFindsFloQuarter($id);
            $this->view->periodtotals = $this->_finds->getFindsFloPeriod($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}