<?php 
/** Controller for all getting data on existing treasure cases
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Treasure_CasesController extends Pas_Controller_Action_Admin {
	
	/** Initialise the ACL and contexts
	*/ 
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->_acl->allow('public',NULL);
    }
	/** Render index pages
	*/ 
	function indexAction() {
		$treasure = new TreasureCases();
		$this->view->treasurecases = $treasure->getCases($this->_getAllParams());
		$current_year = date('Y');
		$years = range(1998, $current_year);
		$yearslist = array();
		foreach($years as $key => $value) {
		$yearslist[] = array('year' => $value);
		}
		$this->view->years = $yearslist;
	}
}