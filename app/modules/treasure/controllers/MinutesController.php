<?php 
/** Controller for rendering the files in the treasure valuation committee minutes folder
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Treasure_MinutesController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/ 
    public function init() {
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow(null);
    }
	/**  Render the index page
	*/ 
	public function indexAction()	
	{
	}
}