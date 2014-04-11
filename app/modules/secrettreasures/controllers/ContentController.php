<?php
/** Controller for all rendering the advice section of the treasure module
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Secrettreasures_ContentController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/
    public function init() {
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow(null);
    }
	/**  Render the index page
	*/
	function indexAction()	{
		$content = new Content();
		$this->view->contents = $content->getSecretContent('secret',$this->getRequest()->getParam('slug'));
	}
}