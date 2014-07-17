<?php
/** Controller for all the Scheme's commissioned reviews
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @author     Daniel Pett <dpett@britishmuseum.org>
* @copyright  Daniel Pett <dpett@britishmuseum.org>
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Publications_ReviewsController extends Pas_Controller_Action_Admin {
	
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow('public',null);
	}
	
	/** Render index pages
	*/ 
	public function indexAction() {
	 	$content = new Content();
		$this->view->contents = $content->getContent('reviews',$this->_getParam('slug'));
    }
}