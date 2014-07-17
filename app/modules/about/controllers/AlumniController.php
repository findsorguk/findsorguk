<?php
/** Controller for former staff
 * 
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
*/
class About_AlumniController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
         * @access public
         */
	public function init() {
            $this->_helper->_acl->allow('public',null);
	}

       /** The index action
        * @access public
        */
	public function indexAction() {
            $alumni = new Contacts();
            $this->view->alumni = $alumni->getAlumniList();
	}
	
        /** Set up view for individual contact
         * @access public
         * @throws Pas_Exception_Param
         */
	public function profileAction() {
            if($this->_getParam('id',false)){
                $id = $this->_getParam('id');
                $staffs = new Contacts();
                $this->view->staffs = $staffs->getPersonDetails($id);
                $findstotals = new Finds();
                $this->view->findstotals = $findstotals->getFindsFloQuarter($id);
                $periodtotals = new Finds();
                $this->view->periodtotals = $periodtotals->getFindsFloPeriod($id);
            } else {
                throw new Pas_Exception_Param($this->_missingParameter);
            }
 	}
}