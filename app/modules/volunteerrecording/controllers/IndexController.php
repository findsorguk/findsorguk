<?php
/** Volunteer recording guide index module
 * @category Pas
 * @package Pas_Controller
 * @subpackage Action
 * @version 1
 * @since May 14 2014
 * @filesource /app/modules/volunteerrecording/controllers/IndexController.php
 * @license GNU
 * @copyright DEJ PETT
 * @author Daniel Pett
 * @author Mary Chester-Kadwell
 */

class Volunteerrecording_IndexController extends Pas_Controller_Action_Admin {
	
	/** Initiate the acl
	 * 
	 */
	public function init() {
		$this->_helper->_acl->allow('public',null);	
	}
	/** Display the index page for the finds recording guide
	* 
	*/
	public function indexAction() {
		$content = new Content();
		$this->view->front = $content->getFrontContent('frg', 1, 3);
		$this->view->contents = $content->getSectionContents('frg');
	}

}

