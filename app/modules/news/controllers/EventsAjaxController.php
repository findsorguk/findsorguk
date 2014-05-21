<?php
/** Provide a feed of mapping data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAjax
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo This could probably be transferred to pull data directly from XML with XSL transform.
*/
class Events_AjaxController extends Pas_Controller_Action_Ajax {

	/** Initialise the ACL for access levels and the contexts
	*/
	public function init() {
		$this->_helper->_acl->allow(NULL);
		$this->_helper->layout->disableLayout();  
    }

    /** Return data for the index action
	*/
	public function indexAction(){
	}
	/** Return data for the event data ajax page
	*/
	public function eventdataAction() {
	$events = new Events();
	$this->view->mapping = $events->getMapdata();
	}
}