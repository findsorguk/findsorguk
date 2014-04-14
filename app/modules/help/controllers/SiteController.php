<?php
/** Controller for index of help for the site topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Help_SiteController extends Pas_Controller_Action_Admin {
	/** Setup the ACL.
	*/
	public function init()  {
	$this->_helper->acl->allow('public',null);
	}
	/** Display help index
	*/		
	public function indexAction() {
	$help = new Help();
	$this->view->help = $help->getTopics($this->_getParam('page'),$section = 'help');
	}
	/** Display an individual topic
	 * 
	*/	
	public function topicAction() {
	$help = new Help();
	$this->view->help = $help->getTopic($section = 'help',$this->_getParam('id'));
	}

}