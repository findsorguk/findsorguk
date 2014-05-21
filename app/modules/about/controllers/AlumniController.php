<?php
/** Controller for alumni based data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Contacts_AlumniController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
		$this->_helper->_acl->allow('public',null);
	}
	
	/** Set up view for index page
	*/ 
	public function indexAction() {
		$alumni = new Contacts();
		$this->view->alumni = $alumni->getAlumniList();
	}
	/** Set up view for individual contact
	*/ 
	public function profileAction()
	{
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