<?php
/** Controller for displaying information topics
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Help_DatabaseController extends Pas_Controller_Action_Admin {

        protected $_help;

        /** Setup the ACL.
	*/
	public function init()  {
	$this->_helper->acl->allow('public',null);
        $this->_help = new Help();
	}
	/** Display the help topics
	*/
	public function indexAction() {
	$this->view->help = $this->_help->getTopics($this->_getParam('page'),
                'databasehelp');
	}
	/** Display an individual topic
	*/
	public function topicAction() {
	$this->view->help = $this->_help->getTopic('databasehelp',
                $this->_getParam('id'));
	}
	
}